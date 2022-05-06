$(function () {

    /* PushMenu()
     * ==========
     * Adds the push menu functionality to the sidebar.
     *
     * @usage: $('.btn').pushMenu(options)
     *          or add [data-toggle="push-menu"] to any button
     *          Pass any option as data-option="value"
     */


    var DataKey = 'lte.pushmenu'

    var Selector = {
        collapsed: '.sidebar-collapse',
        open: '.sidebar-open',
        mainSidebar: '.main-sidebar',
        contentWrapper: '.content-wrapper',
        searchInput: '.sidebar-form .form-control',
        button: '[data-bs-toggle="push-menu"]',
        mini: '.sidebar-mini',
        expanded: '.sidebar-expanded-on-hover',
        layoutFixed: '.fixed',
        sidebar_toggle: '.sidebar-toggle'
    }

    var Default = {
        collapseScreenSize: 767,
        expandOnHover: false,
        expandTransitionDelay: 200
    }

    var ClassName = {
        collapsed: 'sidebar-collapse',
        open: 'sidebar-open',
        mini: 'sidebar-mini',
        expanded: 'sidebar-expanded-on-hover',
        expandFeature: 'sidebar-mini-expand-feature',
        layoutFixed: 'fixed'
    }

    var Event = {
        expanded: 'expanded.pushMenu',
        collapsed: 'collapsed.pushMenu'
    }


    // PushMenu Class Definition
    // =========================
    var PushMenu = function (options) {
        this.options = options
        this.init()
    }

    PushMenu.prototype.init = function () {
        if (this.options.expandOnHover
            || ($('.navigation').is(Selector.mini + Selector.layoutFixed))) {
            this.expandOnHover()
            $('.navigation').addClass(ClassName.expandFeature)
        }

        $(Selector.contentWrapper).on('click', function () {
            // Enable hide menu when clicking on the content-wrapper on small screens
            if ($(window).width() <= this.options.collapseScreenSize && $('.navigation').hasClass(ClassName.open)) {
                this.close()
            }
        }.bind(this))

        // __Fix for android devices
        $(Selector.searchInput).on('click', function (e) {
            e.stopPropagation()
        })
    }

    PushMenu.prototype.toggle = function () {
        var windowWidth = $(window).width()
        var isOpen = !$('.navigation').hasClass(ClassName.collapsed)

        if (windowWidth <= this.options.collapseScreenSize) {
            isOpen = $('.navigation').hasClass(ClassName.open)
        }

        if (!isOpen) {
            this.open()
        } else {
            this.close()
        }
    }

    PushMenu.prototype.open = function () {
        var windowWidth = $(window).width()

        if (windowWidth > this.options.collapseScreenSize) {
            $('.navigation').removeClass(ClassName.collapsed)
                .trigger($.Event(Event.expanded))
        }
        else {
            $('.navigation').addClass(ClassName.open)
                .trigger($.Event(Event.expanded))
        }
        $('.sidebar-toggle.ms-4-5').removeClass('ms-4-5').addClass('ms-15')
        $('.container.main').removeClass('container-not-expanded')
        $('.w-100.bg-white.fixed-bottom').removeClass('ps-4-custom').addClass('ps-14')
    }

    PushMenu.prototype.close = function () {
        var windowWidth = $(window).width()
        if (windowWidth > this.options.collapseScreenSize) {
            $('.navigation').addClass(ClassName.collapsed)
                .trigger($.Event(Event.collapsed))
        } else {
            $('.navigation').removeClass(ClassName.open + ' ' + ClassName.collapsed)
                .trigger($.Event(Event.collapsed))
        }
        $('.sidebar-toggle.ms-15').removeClass('ms-15').addClass('ms-4-5')
        $('.container.main').addClass('container-not-expanded')
        $('.w-100.bg-white.fixed-bottom').removeClass('ps-14').addClass('ps-4-custom')
    }

    PushMenu.prototype.expandOnHover = function () {
        $(Selector.mainSidebar).on('mouseenter', function () {
            if ($('.navigation').is(Selector.mini + Selector.collapsed)
                && $(window).width() > this.options.collapseScreenSize) {
                this.expand()
            }
        }.bind(this), function () {
            if ($('.navigation').is(Selector.expanded)) {
                this.collapse()
            }
        }.bind(this))
    }

    PushMenu.prototype.expand = function () {
        setTimeout(function () {
            $('.navigation').removeClass(ClassName.collapsed)
                .addClass(ClassName.expanded)
        }, this.options.expandTransitionDelay)
    }

    PushMenu.prototype.collapse = function () {
        setTimeout(function () {
            $('.navigation').removeClass(ClassName.expanded)
                .addClass(ClassName.collapsed)
        }, this.options.expandTransitionDelay)
    }

    // PushMenu Plugin Definition
    // ==========================
    function Plugin(option) {
        return this.each(function () {
            var $this = $(this)
            var data = $this.data(DataKey)

            if (!data) {
                var options = $.extend({}, Default, $this.data(), typeof option == 'object' && option)
                $this.data(DataKey, (data = new PushMenu(options)))
            }

            if (option === 'toggle') data.toggle()
        })
    }

    var old = $.fn.pushMenu

    $.fn.pushMenu = Plugin
    $.fn.pushMenu.Constructor = PushMenu

    // No Conflict Mode
    // ================
    $.fn.pushMenu.noConflict = function () {
        $.fn.pushMenu = old
        return this
    }

    // Data API
    // ========
    $(document).on('click', Selector.button, function (e) {
        e.preventDefault()
        Plugin.call($(this), 'toggle')
    })
    $(window).on('load', function () {
        Plugin.call($(Selector.button))
    })
})

$(function () {

    /* Tree()
    * ======
    * Converts a nested list into a multilevel
    * tree view menu.
    *
    * @Usage: $('.my-menu').tree(options)
    *         or add [data-widget="tree"] to the ul element
    *         Pass any option as data-option="value"
    */

    var DataKey = 'lte.tree'

    var Default = {
        animationSpeed: 500,
        accordion: true,
        followLink: false,
        trigger: '.treeview a'
    }

    var Selector = {
        tree: '.tree',
        treeview: '.treeview',
        treeviewMenu: '.treeview-menu',
        open: '.menu-open, .active',
        li: 'li',
        data: '[data-widget="tree"]',
        active: '.active'
    }

    var ClassName = {
        open: 'menu-open',
        tree: 'tree'
    }

    var Event = {
        collapsed: 'collapsed.tree',
        expanded: 'expanded.tree'
    }

    // Tree Class Definition
    // =====================
    var Tree = function (element, options) {
        this.element = element
        this.options = options
        $(this.element).addClass(ClassName.tree)

        $(Selector.treeview + Selector.active, this.element).addClass(ClassName.open)

        this._setUpListeners()
        this._preventClose()
    }

    Tree.prototype.toggle = function (link, event) {
        var treeviewMenu = link.next(Selector.treeviewMenu)
        var parentLi = link.parent()
        var isOpen = parentLi.hasClass(ClassName.open)

        if (!parentLi.is(Selector.treeview)) {
            return
        }

        if (!this.options.followLink || link.attr('href') === '#') {
            event.preventDefault()
        }

        if (isOpen) {
            this.collapse(treeviewMenu, parentLi)
        } else {
            this.expand(treeviewMenu, parentLi)
        }
    }

    Tree.prototype.expand = function (tree, parent) {
        var expandedEvent = $.Event(Event.expanded)

        if (this.options.accordion) {
            var openMenuLi = parent.siblings(Selector.open)
            var openTree = openMenuLi.children(Selector.treeviewMenu)
            this.collapse(openTree, openMenuLi)
        }

        parent.addClass(ClassName.open)
        tree.slideDown(this.options.animationSpeed, function () {
            $(this.element).trigger(expandedEvent)
        }.bind(this))
    }

    Tree.prototype.collapse = function (tree, parentLi) {
        var collapsedEvent = $.Event(Event.collapsed)

        tree.find(Selector.open).removeClass(ClassName.open)
        parentLi.removeClass(ClassName.open)
        tree.slideUp(this.options.animationSpeed, function () {
            tree.find(Selector.open + ' > ' + Selector.treeview).slideUp()
            $(this.element).trigger(collapsedEvent)
        }.bind(this))
    }

    // Private

    Tree.prototype._setUpListeners = function () {
        var that = this
        $(this.element).on('click', this.options.trigger, function (event) {
            that.toggle($(this), event)
        })
    }

    //Evit close <a> when hover ul.treeview-menu
    Tree.prototype._preventClose = function () {
        $('.sidebar-mini').on('mouseenter', function () {
            $(Selector.treeviewMenu).on('mouseenter', function () {
                if ($('.sidebar-mini').hasClass('sidebar-collapse')) {
                    var sibling = this.previousElementSibling
                    sibling.classList.add('active-hover')
                }
            })

            $(Selector.treeviewMenu).on('mouseleave', function (e) {
                if ($('.sidebar-mini').hasClass('sidebar-collapse')) {
                    var sibling = this.previousElementSibling
                    sibling.classList.remove('active-hover')
                }
            })
        })
    }

    // Plugin Definition
    // =================
    function Plugin(option) {
        return this.each(function () {
            var $this = $(this)
            var data = $this.data(DataKey)

            if (!data) {
                var options = $.extend({}, Default, $this.data(), typeof option == 'object' && option)
                $this.data(DataKey, new Tree($this, options))
            }
        })
    }

    var old = $.fn.tree

    $.fn.tree = Plugin
    $.fn.tree.Constructor = Tree

    // No Conflict Mode
    // ================
    $.fn.tree.noConflict = function () {
        $.fn.tree = old
        return this
    }

    // Tree Data API
    // =============
    $(window).on('load', function () {
        $(Selector.data).each(function () {
            Plugin.call($(this))
        })
    })
})
