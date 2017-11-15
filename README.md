# CFX Markets Brokerage SDK for PHP

*A public PHP SDK to access the CFX Markets brokerage API*

This libraries helps to facilitate interactions with the CFX Brokerage through CFX's brokerage REST api. While the API may be used directly, it is much easier to use via this SDK because the SDK handles the details of routing and protocol conformity, and also provides a more intuitive, object-based means of access and input validation.

Before diving in, there are a few things to note about the way the SDK works:

 1. It throws exceptions. You should *assume* that all actions return the expected results, and you should be prepared to handle exceptions if they're thrown. Exceptions are documented at the code level, so make sure to read up on them before hand, and make sure at very least that you're handling general exceptions at an application level to avoid unpleasant system output to the user. Here are a few of the common ones:
    * `\\CFX\\Persistence\\ResourceNotFoundException` -- thrown when an attempt is made to `get` a resource that doesn't exist (e.g., `$cfx->users->get('non-existent')`).
    * `\\CFX\\BadInputException` -- thrown when an attempt is made to `save` a resource that has user input errors. Includes a `getInputErrors` method that returns the raw array of `jsonapi` format errors.
 2. The SDK is broken into a three-part hierarchy: `Context > Datasource > Resource`. The `Context` (`$cfx` in the examples below) is responsible for facilitating interactions between various types of resources; the `Datasource` (`$cfx->users`, `$cfx->assets`, etc...) is responsible for managing persistence and inflation ("unpersistence") for specific resources; and the `Resource` (returned by `$cfx->assets->get('ASST001')`, `$cfx->users->create()`, etc...) is responsible for providing an intuitive and meaningful interface for use of the data within a system.
 3. User input errors happen at the `Resource` level, and can be explored using the `hasErrors(string $field = null) : bool`, `numErrors($field = null) : int`, and `getErrors($field = null) : array` methods. The same methods are used on `save` to produce a `\\CFX\\BadInputException`, so instead of checking for errors manually, you can simply catch that exception if/when it's thrown.

## Examples

The best way to understand the SDK's usage is by example. Following is a step-by-step example guide for how to perform the "Sell Now" functionality in the CFX system, which allows partners to input information into the CFX system that leads to a valid, active sell order for a user's holdings.

### Instantiating the Context

The first thing you'll need to do to use the CFX SDK is instantiate the context. For this, you'll need the API url (either `https://sandbox.apis.cfxtrading.com` or `https://apis.cfxtrading.com`, depending on whether or not you're in production), your api key and secret, and an optional Guzzle5.3 HTTP Client instance (the last parameter, only if you want to specify special options):

```php
$cfx = new \CFX\SDK\Brokerage\Client(
    'https://sandbox.apis.cfxtrading.com',
    'my-api-key-12345',
    'my-secret-abcde'
);
```

Now you can move on to the rest of the flow.

### 1. Add the User to Our System

The first thing you'll need to do is make sure your user is in our system. For each of your users that you add to our system, you'll get back that user's CFX User ID and an OAuth token giving you access to manage the user's account. **You should store both of these in your users table for future use.**

Assuming you don't already have them in your table, you should proceed to create the user in our system:

```php
// Try to create the user with information from your database
try {
    // Assume `$myUser` is your user object from your database
    $user = $cfx->users->create()
        ->setDisplayName($myUser->getName())
        ->setEmail($myUser->getEmail())
        ->setPhoneNumber($myUser->getPhone())
        ->save();

    // Now add the CFX user's id and oauth token to your database
    $myDb->prepare('UPDATE `users` SET `cfxUserId` = ?, `cfxOAuthToken` = ? WHERE `userId` = ?')
        ->execute([ $user->getId(), $user->getOAuthTokens()[0]->getId(), $myUser->getId() ]);

// If the user already exists in our system, you'll have to stop here and bring the user through our
// OAuth flow (not yet implemented)
} catch (\CFX\Persistence\DuplicateResourceException $e) {
    // Redirect to OAuth, then come back when you've got the userId and OAuth token from that
}
```

Now that you have a valid OAuth token, you can use it to go through the rest of the process for the user.

### 2. List or Manage Assets

At this point, you might want to make sure the asset your user is interested in selling is in our system. You'll do that by searching for the asset, then creating an "Asset Intent"[^1] if you don't find results:

```php
// First, try to get the asset as if it were already in our system
try {
    $assetIntent = null;
    $asset = $cfx->assets->get('MYASST001');

// If you get an error, then try to create an asset intent
} catch (\CFX\Persistence\ResourceNotFoundException $e) {
    $asset = null;

    try {
        $assetIntent = $cfx->assetIntent->create()
            ->setName('My Asset')
            ->setDescription("A luxurious condo in Mexico....")
            ->save();

    // If you've already submitted this asset intent, then that intent will be attached to the error and you can use it
    } catch (\CFX\Persistence\DuplicateResourceException $e) {
        $assetIntent = $e->getDuplicateResource();
    }
}
```

### 3. Order Intent

Now you've got a valid CFX user, an asset or asset intent, and presumably you've got everything else you need to initiate an order intent, so let's do it:

```php
// Watch out, could throw exceptions!
try {
    $intent = $cfx->orderIntents->create()
        ->setType("sell")
        ->setAsset($asset)
        ->setAssetIntent($assetIntent)
        ->setUser($user)
        ->setOwnerEntity($user->getPersonEntity())
        ->setNumShares(12345)
        ->setPriceHigh(5.25)
        ->setPriceLow(4.80)
        ->save();
} catch (\CFX\BadInputException $e) {
    // Use this to figure out what the user needs to do
}
```

### 4. Legal Information, Bank Account, ID Documents, Ownership Documents, and Agreement Document

An order intent can't actually be converted into a live order until we've got a number of other pieces of information from the user. These include their SSN (or the tax id of the entity that owns the asset they're selling), their address, their legal name as it appears on their statement, a verified bank account, and a number of documents. Here's what all that might look like in code:

```php
// First, let's add an address for the owner entity
try {
    $personalAddress = $cfx->addresses->create()
        ->setLabel("Home")
        ->setStreet1("555 4th St")
        ->setStreet2("#321")
        ->setCity("Chicago")
        ->setState("IL")
        ->setCountry("USA")
        ->save();

    $user->getPersonEntity()
        ->setPrimaryAddress($personalAddress);

    // Now let's add the user's legal name and SSN and their answers to some questions about their FINRA association and corporate ownership
    $user->getPersonEntity()
        ->setLegalId('123456789')
        ->setLegalName($myUser->getLegalName())
        ->setFinraStatus(true)
        ->setFinraStatusText("My wife works for FINRA")
        ->setCorporateStatus(false)
        ->save();

    // Now add some ID Documents
    $photoId = $cfx->documents->create()
        ->setType('id')
        ->setUrl('https://mydocs.com/my/doc/12345')
        ->setLegalEntity($user->getPersonEntity())
        ->save();

    // Now proof of ownership
    $assetOwnership = $cfx->documents->create()
        ->setType('ownership')
        ->setUrl('https://mydocs.com/my/doc/67890')
        ->setOrderIntent($intent)
        ->save();

    // Now their signed agreement
    $agreement = $cfx->documents->create()
        ->setType("agreement")
        ->setUrl("hellosign:abcde12345")
        ->setOrderIntent($intent)
        ->save();


    // If you got this far, you may have a valid order by now. Check and see
    if ($intent->getOrder()) {
        // Yay!! Now you can show the user information about the order, like its current highest bid, number of bids, etc.
    } else {
        // Womp womp, no order yet :(. Will have to wait for a webhook to inform you when the order intent's status changes.
    }

} catch (\CFX\BadInputException $e) {
    // Do something with this
}
```

> 
> **NOTE:** Document handling in the current version of the API is not ideal. In future versions, you'll create a document, _then_ attach it to a legal entity or an order intent as necessary, and this will trigger updating of that legal entity or intent. As it stands, though, you have to attach the order intent or legal entity to the _document_, which does not trigger updating of the order intent or legal entity. Because of this, you may have to manually refresh the order intent to see whether or not it now has a valid order. You can do this by calling `$intent = $cfx->orderIntents->get("id=".$intent->getId());`
> 


-----------------------------------------------------------------

## Footnotes

[^1]: Since brokerage partners aren't allowed to create arbitrary assets in the system, they have to _request_ that CFX create an asset for them. They do this using an `AssetIntent`.

