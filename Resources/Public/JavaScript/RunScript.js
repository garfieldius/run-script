
/*
 * (c) 2021 Georg Großberger <contact@grossberger-ge.org>
 *
 * This file is free software; you can redistribute it and/or
 * modify it under the terms of the MIT license
 *
 * For the full copyright and license information see the file
 * LICENSE or <https://opensource.org/licenses/MIT>
 */

require(['jquery', 'TYPO3/CMS/Backend/Notification', 'TYPO3/CMS/Core/Ajax/AjaxRequest'], function ($, notify, xhr) {
    function ll(key) {
        return TYPO3.lang[key] || "";
    }

    // Factory for TYPO3s AjaxRequest class
    function xhrGet(url, script, callback) {
        new xhr(TYPO3.settings.ajaxUrls["tx_runscript_" + url])
            .withQueryArguments({script: script})
            .get()
            .then(function (response) {
                response.resolve().then(callback)
            });
    }

    // wait for a couple ms and then fetch the status
    function checkStatus(el) {
        setTimeout(function () {
            xhrGet("status", el.data("script"), function (responseJSON) {
                // Scripts is not running
                if (!responseJSON.running) {
                    // Set as not-active
                    el.find("[data-container=status-idle]").show();
                    el.find("[data-container=status-busy]").hide();
                    el.data("running", false);

                    var reload = parseInt(el.data("reloadBackend")) || 0;

                    // Inform user of user/reload or just success
                    if (responseJSON.error) {
                        notify.error(ll("modal.header.error"), responseJSON.error);
                    } else if (reload > 0) {
                        notify.success(ll("modal.header.success"), ll("message.reload"));
                        setTimeout(function () {
                            top.location.reload();
                        }, reload * 1000);
                    } else {
                        notify.success(ll("modal.header.success"), ll("message.finished"));
                    }
                } else {
                    // Recursive call to check again if still running
                    checkStatus(el);
                }
            });
        }, 700);
    }

    // set an element as active and dispatch the checkStatus
    function setActive(el) {
        el.data("running", true);
        el.find("[data-container=status-idle]").hide();
        el.find("[data-container=status-busy]").show();

        checkStatus(el);
    }

    // Traverse elements in
    function checkAllElementsState() {
        var els = $("[data-toggle=tx_runscript]"),
            completed = 0;

        function done() {
            completed++;

            if (completed >= els.length) {
                setTimeout(checkAllElementsState, 1000);
            }
        }

        els.each(function () {
            var el = $(this);

            if (!el.data("running")) {
                xhrGet("status", el.data("script"), function (responseJSON) {
                    if (responseJSON.running) {
                        setActive(el);
                    }

                    done();
                });
            } else {
                done();
            }
        });
    }

    // Fetch initial state on load
    checkAllElementsState();

    // Handle script start on click
    $(document).on("click", "[data-toggle=tx_runscript]", function (event) {
        // event.target is usually a child of the actual tag
        var el = $(event.target).closest("[data-toggle=tx_runscript]");

        event.preventDefault();

        if (el.data("running")) {
            return;
        }

       xhrGet("start", el.data("script"), function (responseJSON) {
            if (responseJSON.running) {
                setActive(el);
                notify.info(ll("modal.header.running"), responseJSON.message);
            } else {
                notify.error(ll("modal.header.error"), responseJSON.error || responseJSON.message);
            }
        });
    });
});
