<img src="http://i.imgur.com/fw4Euqk.png" alt="Grabber" align="left" height="60" />

# Grabber *for Craft CMS*
Grabber adds numerous methods to quickly grab useful content

##Table of Contents

- [Entry](#entry)
- [Section](#section)
- [Content](#content)
- [Page](#page)
- [Classes](#classes)
- [Global](#global)
- [Link](#link)
- [Plugin](#plugin)

## Entry

Grab an array of commonly used Entry data by defining just the id or slug and the entries given section if required.

| # | Parameter            | Type              | Default          | Description
--- | -------------------- | ----------------- | ---------------- | -----------
| 1 | entry slug or ID     | string or integer | null             | The field slug
| 2 | section handle or ID | string or integer | current entry ID | [optional] The section handle
| 3 | true or false        | boolean           | false            | [optional] If ```true```, the entire given entry object will be returned, rather than common attributes

The first time an entry is queried, it will be cached. So any additional queries to the same entry per page load will revert to the cached version.

By default, the following data will be collected:

| Data    | Type    | Description
| ------- | ------- | -----------
| ID      | string  | Entry ID
| Title   | string  | Entry title
| Slug    | string  | Entry slug
| Url     | string  | Entry absolute url
| Uri     | string  | Entry relative url
| Snippet | string  | Entry Snippet
| Status  | string  | Entry status
| Level   | string  | Entry hiarachy level
| Parent  | boolean | Checks if entry has a parent
| Child   | boolean | Checks if entry has a child
| Type    | string  | Returns Channel, Structure, Single, or Category
| Section | array   | Entry section details. See section function above

If the entry can not be found; the given data will be used to make a 'best-guess' fallback. This is ideal for the likes of error pages, or category/tag templates.

### Example

```
{{ grab.entry(2)['title'] }}
```

```
{{ grab.entry('Welcome to my blog', 'news', true) }}
```

----

## Section

Similar to [Entry](#entry), this will grab a section and return an array of commonly used data by defining the section id or handle.

By default, the following data will be collected:

| Data    | Type   | Description
| ------- | ------ | -----------
| Title   | string | Section title
| Name    | string | Section name
| ID      | string | Section ID
| Handle  | string | Section handle
| Type    | string | Section Type - Channel, Structure, or Single
| Url     | string | Section absolute url
| Uri     | string | Section relative url

If the section can not be found; the given data will be used to make a 'best-guess' fallback. This is ideal for the likes of error pages, or category/tag templates.

### Example

```
{{ grab.section('news')['id'] }}
```

----

## Content

Grab a specific field from a particular entry.

| # | Parameter            | Type              | Default          | Description
--- | -------------------- | ----------------- | ---------------- | -----------
| 1 | field handle         | string            | null             | The field slug
| 2 | entry slug or ID     | string or integer | current entry ID | Should be either the entry handle or id.
| 3 | section handle or ID | string or integer | null             | [optional] The third parameter is optional and is for the section type. Results will be more accurate if the section is defined since it's possible to have the same entry slug across multiple sections. Omitting this will return the first instance of a successful entry handle match.

### Example
```
{{ grab.content('gallery', 405, 'blog') }}
```
----

## Classes

This service will automatically grab a list of useful class names bespoke to users current page, and return them as a string:

| Example        | Description
 --------------- | ---------------------
| id-2           | The current entry ID
| homepage       | The current entry slug
| parent         | Added if the current entry is a parent to another entry
| child          | Added if the current entry is a child to another entry
| level-2        | If the parent or child is set, the level number is also included
| single         | The current section type
| home           | The current section handle
| page-number-2  | If the user if on a paginated page, the current page number will be added
| local          | Added if you are running the site on a local server
| error-404      | Added if the page is on an error page

Any duplicate strings will be omitted.

If you have my [Browser](https://github.com/marknotton/craft-plugin-browser) plugin installed; additional data will be included into the class list.

| Example   | Description
 ---------- | ---------------------
| desktop   | If user is on a desktop machine
| tablet    | If user is on a tablet device
| mobile    | If user is on a mobile device
| mac       | Adds the users device type.

### Example
```
{{ grab.classes }}
```
### Additional Classes

If you want to add additional classes from the template side of things; you can define a twig variable called *classes*. Make sure to add the grabber hook after the classes variable.

### Todo

- Add 'tag' or 'category' if on one of those page types.
- Add tag slug or category slug if on one of those page types.

```
{% set classes = "extra-class" %}
{% hook 'grabber' %}
```

----

## Page

Grabs the current page title and fallbacks to the homepage is nothing is found. Designed to be be used as ID for the body tag.

```
{{ grab.page }}
```

----

## Global

Grab global data from a specific set. This will perform a few checks to ensure the global exists before returning anything.

| # | Parameter        | Type              | Description
--- | ---------------- | ----------------- | -----------
| 1 | Field slug       | string            | The field slug
| 2 | Set handle or ID | string or integer | [optional] The section handle or ID

### Example
```
{{ grab.global('facebook', 'social')}}
```

```
{{ grab.global('facebook') }}
```

----

## Link

Coming Soon


----

## Plugin

Returns a boolean after checking if a plugin is installed and enabled.

```
{{ grab.plugin('pluginName') }}
```

Returns a boolean after checking if a plugin is installed only.
```
{{ grab.plugin('pluginName', true) }}
```
