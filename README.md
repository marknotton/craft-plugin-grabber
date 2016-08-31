<img src="http://i.imgur.com/fw4Euqk.png" alt="Grabber" align="left" height="60" />

# Grabber *for Craft CMS*
Grabber adds numerous methods to quickly grab useful content

##Table of Contents

- [Classes](#classes)
  - [Example](#example)
  - [Additional Classes](#additional-classes)
- [Content](#)
  - [Settings](#)
  - [Examples](#)
- [Entry](#)
  - [Settings](#)
  - [Examples](#)
- [Global](#)
  - [Settings](#)
  - [Examples](#)
- [Link](#)
  - [Settings](#)
  - [Examples](#)
  - [Twig Extensions](#)
    - [Settings](#)
    - [Examples](#)
- [Page](#)
  - [Settings](#)
  - [Examples](#)
- [Plugin](#)
  - [Settings](#)
  - [Examples](#)
- [Section](#)
  - [Settings](#)
  - [Examples](#)
- [Globals](#)

## Classes

Classes will automatically collect a list of useful classes bespoke to users current page, and return them as a string:

| Example        | Description
 --------------- | ---------------------
| id-2           | The current entry ID
| homepage       | The current entry slug
| parent         | Added only if the current entry is a parent to another entry
| child          | Added only if the current entry is a child to another entry
| level-2        | If the parent or child is set, the level number is also included
| single         | The current section type
| home           | The current section handle
| page-number-2  | If the user if on a paginated page, the current page number will be added
| local          | Added if you are running the site on a local server

If you have [Browser](https://github.com/marknotton/craft-plugin-browser) plugin installed; additional data will be included into the class list.

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

```
{% set classes = "extra-class" %}
{% hook 'grabber' %}
```

----
## Content

Coming Soon

----
## Entry

Coming Soon

----
## Global

Coming Soon

----
## Link

Coming Soon

----
## Page

Coming Soon

----
## Plugin

Coming Soon

----
## Section

Coming Soon

----
## Globals

Coming Soon
