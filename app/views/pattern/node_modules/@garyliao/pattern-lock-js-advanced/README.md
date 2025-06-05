# pattern-lock-js
A passcode mechanism built with scalable vector graphics (SVG) and javascript for modern web application with mobile and tablet support

## [Demo](https://tympanix.github.io/pattern-lock-js/)

## Install
Install using npm:
```
npm i @garyliao/pattern-lock-js-advanced
```

## Getting started
Import dependecies:
```html
<script src="jquery.js" charset="utf-8"></script>
```
Import the module:
```html
<link rel="stylesheet" href="patternlock.min.css">
<script src="patternlock.min.js" charset="utf-8"></script>
```

Design your desired svg pattern (or use the default one below). Your svg graphics must as a minimum have the `patternlock` class and three groups `<g>` with the classes `lock-actives`, `lock-lines` and `lock-dots`
```html
<svg class="patternlock" id="lock" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
    <g class="lock-actives"></g>
    <g class="lock-lines"></g>
    <g class="lock-dots">
        <circle cx="20" cy="20" r="2"/>
        <circle cx="50" cy="20" r="2"/>
        <circle cx="80" cy="20" r="2"/>

        <circle cx="20" cy="50" r="2"/>
        <circle cx="50" cy="50" r="2"/>
        <circle cx="80" cy="50" r="2"/>

        <circle cx="20" cy="80" r="2"/>
        <circle cx="50" cy="80" r="2"/>
        <circle cx="80" cy="80" r="2"/>
    </g>
</svg>
```
Initialise the component
```javascript
var lock = new PatternLock("#lock", {
  onPattern: function(result) {
    console.log(result.rspCode);
    // check rspCode equals 0000 before you retrieve result.pattern
   }
});
```

## Options
The returned object from `new PatternLock(...)` has the following utility functions:
* **`clear()`** Clears the current pattern
* **`success()`** Validates the pattern as correct
* **`error()`** Validates the pattern as incorrect
* **`switchHide()`** Hide or Show line when running 
* **`getPattern()`** Return an object contains rspCode and pattern result.    
  * `0000` -> Success
  * `1001` -> Input nodes number invalid(Smaller than minimun number)
  * `1002` -> Input nodes number invalid(Exceed manimun accepted number)

The pattern lock constructor accepts a second argument - an object literal with the following properties:

* **`onPattern: function`** Called when a pattern is drawn with the pattern as argument. Returning true/false validates/invalidates the pattern - the same as calling `success()` and `error()`. The context is set to the pattern lock instance itself.
* **`allowRepeat: boolean`** Defines if nodes can be selected repeatly(But can't select one node continuosly). Default: `true`
* **`hideLine: boolean`** Hide the lines if needed. Default: `false`
* **`checkMin: boolean`** Defines if need check min input nodes. Default: `true`
* **`checkMax: boolean`** Defines if need check max input nodes. Default: `true`
* **`min: number`** Defines the minimum valid numbers of input nodes. Default: `4`
* **`max: number`** Defines the maximun valid numbers of input nodes. Default: `9`
