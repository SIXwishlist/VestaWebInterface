/*!
Waypoints - 3.1.1
Copyright © 2011-2015 Caleb Troughton
Licensed under the MIT license.
https://github.com/imakewebthings/waypoints/blog/master/licenses.txt
*/
(function() {
  'use strict'

  var keyCounter = 0
  var allWaypoints = {***REMOVED***

  /* http://imakewebthings.com/waypoints/api/waypoint */
  function Waypoint(options) {
    if (!options) {
      throw new Error('No options passed to Waypoint constructor')
    ***REMOVED***
    if (!options.element) {
      throw new Error('No element option passed to Waypoint constructor')
    ***REMOVED***
    if (!options.handler) {
      throw new Error('No handler option passed to Waypoint constructor')
    ***REMOVED***

    this.key = 'waypoint-' + keyCounter
    this.options = Waypoint.Adapter.extend({***REMOVED***, Waypoint.defaults, options)
    this.element = this.options.element
    this.adapter = new Waypoint.Adapter(this.element)
    this.callback = options.handler
    this.axis = this.options.horizontal ? 'horizontal' : 'vertical'
    this.enabled = this.options.enabled
    this.triggerPoint = null
    this.group = Waypoint.Group.findOrCreate({
      name: this.options.group,
      axis: this.axis
    ***REMOVED***)
    this.context = Waypoint.Context.findOrCreateByElement(this.options.context)

    if (Waypoint.offsetAliases[this.options.offset]) {
      this.options.offset = Waypoint.offsetAliases[this.options.offset]
    ***REMOVED***
    this.group.add(this)
    this.context.add(this)
    allWaypoints[this.key] = this
    keyCounter += 1
  ***REMOVED***

  /* Private */
  Waypoint.prototype.queueTrigger = function(direction) {
    this.group.queueTrigger(this, direction)
  ***REMOVED***

  /* Private */
  Waypoint.prototype.trigger = function(args) {
    if (!this.enabled) {
      return
    ***REMOVED***
    if (this.callback) {
      this.callback.apply(this, args)
    ***REMOVED***
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/destroy */
  Waypoint.prototype.destroy = function() {
    this.context.remove(this)
    this.group.remove(this)
    delete allWaypoints[this.key]
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/disable */
  Waypoint.prototype.disable = function() {
    this.enabled = false
    return this
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/enable */
  Waypoint.prototype.enable = function() {
    this.context.refresh()
    this.enabled = true
    return this
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/next */
  Waypoint.prototype.next = function() {
    return this.group.next(this)
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/previous */
  Waypoint.prototype.previous = function() {
    return this.group.previous(this)
  ***REMOVED***

  /* Private */
  Waypoint.invokeAll = function(method) {
    var allWaypointsArray = []
    for (var waypointKey in allWaypoints) {
      allWaypointsArray.push(allWaypoints[waypointKey])
    ***REMOVED***
    for (var i = 0, end = allWaypointsArray.length; i < end; i++) {
      allWaypointsArray[i][method]()
    ***REMOVED***
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/destroy-all */
  Waypoint.destroyAll = function() {
    Waypoint.invokeAll('destroy')
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/disable-all */
  Waypoint.disableAll = function() {
    Waypoint.invokeAll('disable')
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/enable-all */
  Waypoint.enableAll = function() {
    Waypoint.invokeAll('enable')
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/refresh-all */
  Waypoint.refreshAll = function() {
    Waypoint.Context.refreshAll()
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/viewport-height */
  Waypoint.viewportHeight = function() {
    return window.innerHeight || document.documentElement.clientHeight
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/viewport-width */
  Waypoint.viewportWidth = function() {
    return document.documentElement.clientWidth
  ***REMOVED***

  Waypoint.adapters = []

  Waypoint.defaults = {
    context: window,
    continuous: true,
    enabled: true,
    group: 'default',
    horizontal: false,
    offset: 0
  ***REMOVED***

  Waypoint.offsetAliases = {
    'bottom-in-view': function() {
      return this.context.innerHeight() - this.adapter.outerHeight()
    ***REMOVED***,
    'right-in-view': function() {
      return this.context.innerWidth() - this.adapter.outerWidth()
    ***REMOVED***
  ***REMOVED***

  window.Waypoint = Waypoint
***REMOVED***())
;(function() {
  'use strict'

  function requestAnimationFrameShim(callback) {
    window.setTimeout(callback, 1000 / 60)
  ***REMOVED***

  var keyCounter = 0
  var contexts = {***REMOVED***
  var Waypoint = window.Waypoint
  var oldWindowLoad = window.onload

  /* http://imakewebthings.com/waypoints/api/context */
  function Context(element) {
    this.element = element
    this.Adapter = Waypoint.Adapter
    this.adapter = new this.Adapter(element)
    this.key = 'waypoint-context-' + keyCounter
    this.didScroll = false
    this.didResize = false
    this.oldScroll = {
      x: this.adapter.scrollLeft(),
      y: this.adapter.scrollTop()
    ***REMOVED***
    this.waypoints = {
      vertical: {***REMOVED***,
      horizontal: {***REMOVED***
    ***REMOVED***

    element.waypointContextKey = this.key
    contexts[element.waypointContextKey] = this
    keyCounter += 1

    this.createThrottledScrollHandler()
    this.createThrottledResizeHandler()
  ***REMOVED***

  /* Private */
  Context.prototype.add = function(waypoint) {
    var axis = waypoint.options.horizontal ? 'horizontal' : 'vertical'
    this.waypoints[axis][waypoint.key] = waypoint
    this.refresh()
  ***REMOVED***

  /* Private */
  Context.prototype.checkEmpty = function() {
    var horizontalEmpty = this.Adapter.isEmptyObject(this.waypoints.horizontal)
    var verticalEmpty = this.Adapter.isEmptyObject(this.waypoints.vertical)
    if (horizontalEmpty && verticalEmpty) {
      this.adapter.off('.waypoints')
      delete contexts[this.key]
    ***REMOVED***
  ***REMOVED***

  /* Private */
  Context.prototype.createThrottledResizeHandler = function() {
    var self = this

    function resizeHandler() {
      self.handleResize()
      self.didResize = false
    ***REMOVED***

    this.adapter.on('resize.waypoints', function() {
      if (!self.didResize) {
        self.didResize = true
        Waypoint.requestAnimationFrame(resizeHandler)
      ***REMOVED***
    ***REMOVED***)
  ***REMOVED***

  /* Private */
  Context.prototype.createThrottledScrollHandler = function() {
    var self = this
    function scrollHandler() {
      self.handleScroll()
      self.didScroll = false
    ***REMOVED***

    this.adapter.on('scroll.waypoints', function() {
      if (!self.didScroll || Waypoint.isTouch) {
        self.didScroll = true
        Waypoint.requestAnimationFrame(scrollHandler)
      ***REMOVED***
    ***REMOVED***)
  ***REMOVED***

  /* Private */
  Context.prototype.handleResize = function() {
    Waypoint.Context.refreshAll()
  ***REMOVED***

  /* Private */
  Context.prototype.handleScroll = function() {
    var triggeredGroups = {***REMOVED***
    var axes = {
      horizontal: {
        newScroll: this.adapter.scrollLeft(),
        oldScroll: this.oldScroll.x,
        forward: 'right',
        backward: 'left'
      ***REMOVED***,
      vertical: {
        newScroll: this.adapter.scrollTop(),
        oldScroll: this.oldScroll.y,
        forward: 'down',
        backward: 'up'
      ***REMOVED***
    ***REMOVED***

    for (var axisKey in axes) {
      var axis = axes[axisKey]
      var isForward = axis.newScroll > axis.oldScroll
      var direction = isForward ? axis.forward : axis.backward

      for (var waypointKey in this.waypoints[axisKey]) {
        var waypoint = this.waypoints[axisKey][waypointKey]
        var wasBeforeTriggerPoint = axis.oldScroll < waypoint.triggerPoint
        var nowAfterTriggerPoint = axis.newScroll >= waypoint.triggerPoint
        var crossedForward = wasBeforeTriggerPoint && nowAfterTriggerPoint
        var crossedBackward = !wasBeforeTriggerPoint && !nowAfterTriggerPoint
        if (crossedForward || crossedBackward) {
          waypoint.queueTrigger(direction)
          triggeredGroups[waypoint.group.id] = waypoint.group
        ***REMOVED***
      ***REMOVED***
    ***REMOVED***

    for (var groupKey in triggeredGroups) {
      triggeredGroups[groupKey].flushTriggers()
    ***REMOVED***

    this.oldScroll = {
      x: axes.horizontal.newScroll,
      y: axes.vertical.newScroll
    ***REMOVED***
  ***REMOVED***

  /* Private */
  Context.prototype.innerHeight = function() {
    /*eslint-disable eqeqeq */
    if (this.element == this.element.window) {
      return Waypoint.viewportHeight()
    ***REMOVED***
    /*eslint-enable eqeqeq */
    return this.adapter.innerHeight()
  ***REMOVED***

  /* Private */
  Context.prototype.remove = function(waypoint) {
    delete this.waypoints[waypoint.axis][waypoint.key]
    this.checkEmpty()
  ***REMOVED***

  /* Private */
  Context.prototype.innerWidth = function() {
    /*eslint-disable eqeqeq */
    if (this.element == this.element.window) {
      return Waypoint.viewportWidth()
    ***REMOVED***
    /*eslint-enable eqeqeq */
    return this.adapter.innerWidth()
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/context-destroy */
  Context.prototype.destroy = function() {
    var allWaypoints = []
    for (var axis in this.waypoints) {
      for (var waypointKey in this.waypoints[axis]) {
        allWaypoints.push(this.waypoints[axis][waypointKey])
      ***REMOVED***
    ***REMOVED***
    for (var i = 0, end = allWaypoints.length; i < end; i++) {
      allWaypoints[i].destroy()
    ***REMOVED***
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/context-refresh */
  Context.prototype.refresh = function() {
    /*eslint-disable eqeqeq */
    var isWindow = this.element == this.element.window
    /*eslint-enable eqeqeq */
    var contextOffset = this.adapter.offset()
    var triggeredGroups = {***REMOVED***
    var axes

    this.handleScroll()
    axes = {
      horizontal: {
        contextOffset: isWindow ? 0 : contextOffset.left,
        contextScroll: isWindow ? 0 : this.oldScroll.x,
        contextDimension: this.innerWidth(),
        oldScroll: this.oldScroll.x,
        forward: 'right',
        backward: 'left',
        offsetProp: 'left'
      ***REMOVED***,
      vertical: {
        contextOffset: isWindow ? 0 : contextOffset.top,
        contextScroll: isWindow ? 0 : this.oldScroll.y,
        contextDimension: this.innerHeight(),
        oldScroll: this.oldScroll.y,
        forward: 'down',
        backward: 'up',
        offsetProp: 'top'
      ***REMOVED***
    ***REMOVED***

    for (var axisKey in axes) {
      var axis = axes[axisKey]
      for (var waypointKey in this.waypoints[axisKey]) {
        var waypoint = this.waypoints[axisKey][waypointKey]
        var adjustment = waypoint.options.offset
        var oldTriggerPoint = waypoint.triggerPoint
        var elementOffset = 0
        var freshWaypoint = oldTriggerPoint == null
        var contextModifier, wasBeforeScroll, nowAfterScroll
        var triggeredBackward, triggeredForward

        if (waypoint.element !== waypoint.element.window) {
          elementOffset = waypoint.adapter.offset()[axis.offsetProp]
        ***REMOVED***

        if (typeof adjustment === 'function') {
          adjustment = adjustment.apply(waypoint)
        ***REMOVED***
        else if (typeof adjustment === 'string') {
          adjustment = parseFloat(adjustment)
          if (waypoint.options.offset.indexOf('%') > - 1) {
            adjustment = Math.ceil(axis.contextDimension * adjustment / 100)
          ***REMOVED***
        ***REMOVED***

        contextModifier = axis.contextScroll - axis.contextOffset
        waypoint.triggerPoint = elementOffset + contextModifier - adjustment
        wasBeforeScroll = oldTriggerPoint < axis.oldScroll
        nowAfterScroll = waypoint.triggerPoint >= axis.oldScroll
        triggeredBackward = wasBeforeScroll && nowAfterScroll
        triggeredForward = !wasBeforeScroll && !nowAfterScroll

        if (!freshWaypoint && triggeredBackward) {
          waypoint.queueTrigger(axis.backward)
          triggeredGroups[waypoint.group.id] = waypoint.group
        ***REMOVED***
        else if (!freshWaypoint && triggeredForward) {
          waypoint.queueTrigger(axis.forward)
          triggeredGroups[waypoint.group.id] = waypoint.group
        ***REMOVED***
        else if (freshWaypoint && axis.oldScroll >= waypoint.triggerPoint) {
          waypoint.queueTrigger(axis.forward)
          triggeredGroups[waypoint.group.id] = waypoint.group
        ***REMOVED***
      ***REMOVED***
    ***REMOVED***

    for (var groupKey in triggeredGroups) {
      triggeredGroups[groupKey].flushTriggers()
    ***REMOVED***

    return this
  ***REMOVED***

  /* Private */
  Context.findOrCreateByElement = function(element) {
    return Context.findByElement(element) || new Context(element)
  ***REMOVED***

  /* Private */
  Context.refreshAll = function() {
    for (var contextId in contexts) {
      contexts[contextId].refresh()
    ***REMOVED***
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/context-find-by-element */
  Context.findByElement = function(element) {
    return contexts[element.waypointContextKey]
  ***REMOVED***

  window.onload = function() {
    if (oldWindowLoad) {
      oldWindowLoad()
    ***REMOVED***
    Context.refreshAll()
  ***REMOVED***

  Waypoint.requestAnimationFrame = function(callback) {
    var requestFn = window.requestAnimationFrame ||
      window.mozRequestAnimationFrame ||
      window.webkitRequestAnimationFrame ||
      requestAnimationFrameShim
    requestFn.call(window, callback)
  ***REMOVED***
  Waypoint.Context = Context
***REMOVED***())
;(function() {
  'use strict'

  function byTriggerPoint(a, b) {
    return a.triggerPoint - b.triggerPoint
  ***REMOVED***

  function byReverseTriggerPoint(a, b) {
    return b.triggerPoint - a.triggerPoint
  ***REMOVED***

  var groups = {
    vertical: {***REMOVED***,
    horizontal: {***REMOVED***
  ***REMOVED***
  var Waypoint = window.Waypoint

  /* http://imakewebthings.com/waypoints/api/group */
  function Group(options) {
    this.name = options.name
    this.axis = options.axis
    this.id = this.name + '-' + this.axis
    this.waypoints = []
    this.clearTriggerQueues()
    groups[this.axis][this.name] = this
  ***REMOVED***

  /* Private */
  Group.prototype.add = function(waypoint) {
    this.waypoints.push(waypoint)
  ***REMOVED***

  /* Private */
  Group.prototype.clearTriggerQueues = function() {
    this.triggerQueues = {
      up: [],
      down: [],
      left: [],
      right: []
    ***REMOVED***
  ***REMOVED***

  /* Private */
  Group.prototype.flushTriggers = function() {
    for (var direction in this.triggerQueues) {
      var waypoints = this.triggerQueues[direction]
      var reverse = direction === 'up' || direction === 'left'
      waypoints.sort(reverse ? byReverseTriggerPoint : byTriggerPoint)
      for (var i = 0, end = waypoints.length; i < end; i += 1) {
        var waypoint = waypoints[i]
        if (waypoint.options.continuous || i === waypoints.length - 1) {
          waypoint.trigger([direction])
        ***REMOVED***
      ***REMOVED***
    ***REMOVED***
    this.clearTriggerQueues()
  ***REMOVED***

  /* Private */
  Group.prototype.next = function(waypoint) {
    this.waypoints.sort(byTriggerPoint)
    var index = Waypoint.Adapter.inArray(waypoint, this.waypoints)
    var isLast = index === this.waypoints.length - 1
    return isLast ? null : this.waypoints[index + 1]
  ***REMOVED***

  /* Private */
  Group.prototype.previous = function(waypoint) {
    this.waypoints.sort(byTriggerPoint)
    var index = Waypoint.Adapter.inArray(waypoint, this.waypoints)
    return index ? this.waypoints[index - 1] : null
  ***REMOVED***

  /* Private */
  Group.prototype.queueTrigger = function(waypoint, direction) {
    this.triggerQueues[direction].push(waypoint)
  ***REMOVED***

  /* Private */
  Group.prototype.remove = function(waypoint) {
    var index = Waypoint.Adapter.inArray(waypoint, this.waypoints)
    if (index > -1) {
      this.waypoints.splice(index, 1)
    ***REMOVED***
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/first */
  Group.prototype.first = function() {
    return this.waypoints[0]
  ***REMOVED***

  /* Public */
  /* http://imakewebthings.com/waypoints/api/last */
  Group.prototype.last = function() {
    return this.waypoints[this.waypoints.length - 1]
  ***REMOVED***

  /* Private */
  Group.findOrCreate = function(options) {
    return groups[options.axis][options.name] || new Group(options)
  ***REMOVED***

  Waypoint.Group = Group
***REMOVED***())
;(function() {
  'use strict'

  var Waypoint = window.Waypoint

  function isWindow(element) {
    return element === element.window
  ***REMOVED***

  function getWindow(element) {
    if (isWindow(element)) {
      return element
    ***REMOVED***
    return element.defaultView
  ***REMOVED***

  function NoFrameworkAdapter(element) {
    this.element = element
    this.handlers = {***REMOVED***
  ***REMOVED***

  NoFrameworkAdapter.prototype.innerHeight = function() {
    var isWin = isWindow(this.element)
    return isWin ? this.element.innerHeight : this.element.clientHeight
  ***REMOVED***

  NoFrameworkAdapter.prototype.innerWidth = function() {
    var isWin = isWindow(this.element)
    return isWin ? this.element.innerWidth : this.element.clientWidth
  ***REMOVED***

  NoFrameworkAdapter.prototype.off = function(event, handler) {
    function removeListeners(element, listeners, handler) {
      for (var i = 0, end = listeners.length - 1; i < end; i++) {
        var listener = listeners[i]
        if (!handler || handler === listener) {
          element.removeEventListener(listener)
        ***REMOVED***
      ***REMOVED***
    ***REMOVED***

    var eventParts = event.split('.')
    var eventType = eventParts[0]
    var namespace = eventParts[1]
    var element = this.element

    if (namespace && this.handlers[namespace] && eventType) {
      removeListeners(element, this.handlers[namespace][eventType], handler)
      this.handlers[namespace][eventType] = []
    ***REMOVED***
    else if (eventType) {
      for (var ns in this.handlers) {
        removeListeners(element, this.handlers[ns][eventType] || [], handler)
        this.handlers[ns][eventType] = []
      ***REMOVED***
    ***REMOVED***
    else if (namespace && this.handlers[namespace]) {
      for (var type in this.handlers[namespace]) {
        removeListeners(element, this.handlers[namespace][type], handler)
      ***REMOVED***
      this.handlers[namespace] = {***REMOVED***
    ***REMOVED***
  ***REMOVED***

  /* Adapted from jQuery 1.x offset() */
  NoFrameworkAdapter.prototype.offset = function() {
    if (!this.element.ownerDocument) {
      return null
    ***REMOVED***

    var documentElement = this.element.ownerDocument.documentElement
    var win = getWindow(this.element.ownerDocument)
    var rect = {
      top: 0,
      left: 0
    ***REMOVED***

    if (this.element.getBoundingClientRect) {
      rect = this.element.getBoundingClientRect()
    ***REMOVED***

    return {
      top: rect.top + win.pageYOffset - documentElement.clientTop,
      left: rect.left + win.pageXOffset - documentElement.clientLeft
    ***REMOVED***
  ***REMOVED***

  NoFrameworkAdapter.prototype.on = function(event, handler) {
    var eventParts = event.split('.')
    var eventType = eventParts[0]
    var namespace = eventParts[1] || '__default'
    var nsHandlers = this.handlers[namespace] = this.handlers[namespace] || {***REMOVED***
    var nsTypeList = nsHandlers[eventType] = nsHandlers[eventType] || []

    nsTypeList.push(handler)
    this.element.addEventListener(eventType, handler)
  ***REMOVED***

  NoFrameworkAdapter.prototype.outerHeight = function(includeMargin) {
    var height = this.innerHeight()
    var computedStyle

    if (includeMargin && !isWindow(this.element)) {
      computedStyle = window.getComputedStyle(this.element)
      height += parseInt(computedStyle.marginTop, 10)
      height += parseInt(computedStyle.marginBottom, 10)
    ***REMOVED***

    return height
  ***REMOVED***

  NoFrameworkAdapter.prototype.outerWidth = function(includeMargin) {
    var width = this.innerWidth()
    var computedStyle

    if (includeMargin && !isWindow(this.element)) {
      computedStyle = window.getComputedStyle(this.element)
      width += parseInt(computedStyle.marginLeft, 10)
      width += parseInt(computedStyle.marginRight, 10)
    ***REMOVED***

    return width
  ***REMOVED***

  NoFrameworkAdapter.prototype.scrollLeft = function() {
    var win = getWindow(this.element)
    return win ? win.pageXOffset : this.element.scrollLeft
  ***REMOVED***

  NoFrameworkAdapter.prototype.scrollTop = function() {
    var win = getWindow(this.element)
    return win ? win.pageYOffset : this.element.scrollTop
  ***REMOVED***

  NoFrameworkAdapter.extend = function() {
    var args = Array.prototype.slice.call(arguments)

    function merge(target, obj) {
      if (typeof target === 'object' && typeof obj === 'object') {
        for (var key in obj) {
          if (obj.hasOwnProperty(key)) {
            target[key] = obj[key]
          ***REMOVED***
        ***REMOVED***
      ***REMOVED***

      return target
    ***REMOVED***

    for (var i = 1, end = args.length; i < end; i++) {
      merge(args[0], args[i])
    ***REMOVED***
    return args[0]
  ***REMOVED***

  NoFrameworkAdapter.inArray = function(element, array, i) {
    return array == null ? -1 : array.indexOf(element, i)
  ***REMOVED***

  NoFrameworkAdapter.isEmptyObject = function(obj) {
    /* eslint no-unused-vars: 0 */
    for (var name in obj) {
      return false
    ***REMOVED***
    return true
  ***REMOVED***

  Waypoint.adapters.push({
    name: 'noframework',
    Adapter: NoFrameworkAdapter
  ***REMOVED***)
  Waypoint.Adapter = NoFrameworkAdapter
***REMOVED***())
;