# ENGAGEBAY-API

This PHP library provides functionalities for managing contacts through the EngageBay API.

### Version 0.14
Retro compatibility with 7.3

### Version 0.4

It includes new fix NOT FOUND + New method in Contact.

## Features

- **NOT FOUND in ConactList:** NOT FOUND tag when list is empty.
- **New method called addTag added to Contact class:** Add individual tag.

### Version 0.3

It includes new fix to PSR4 class namespace and new alias in Contact Entity (save & delete).

## Features

- **Delete Contact alias:** Easily delete contact.
- **Save Contact alias:** Abstraction of create & delete contact.
- **Not found contact list**

### Version 0.2

It includes the ability to delete Contact.

## Features

- **Delete Contact:** Easily delete contact.

### Version 0.1

It includes the ability to create, update, and search for contacts by ID or email.

## Features

- **Create Contact:** Easily create new contacts with various attributes.
- **Update Contact:** Update existing contact details using their unique identifiers.
- **Search Contact:** Search for contacts using either their ID or email address.

## Installation

Installation using Composer:

```bash
composer require aika/engagebay-api
