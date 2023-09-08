
/*
 * (c) 2021 Georg Großberger <contact@grossberger-ge.org>
 *
 * This file is free software; you can redistribute it and/or
 * modify it under the terms of the MIT license
 *
 * For the full copyright and license information see the file
 * LICENSE or <https://opensource.org/licenses/MIT>
 */

import DocumentService from '@typo3/core/document-service.js'
import RegularEvent from '@typo3/core/event/regular-event.js';
import AjaxRequest from '@typo3/core/ajax/ajax-request.js';

DocumentService.ready().then(() => {
    function ll(key) {
        return TYPO3.lang[key] || "";
    }

    // Factory for TYPO3s AjaxRequest class
    function xhrGet(url, script, callback) {
        new AjaxRequest(TYPO3.settings.ajaxUrls["tx_runscript_" + url])
            .withQueryArguments({script: script})
            .get()
            .then(function (response) {
                response.resolve().then(callback)
            });
    }

    // wait for a couple ms and then fetch the status
    function checkStatus(el) {
        setTimeout(function () {
            xhrGet("status", el.dataset.script, function (responseJSON) {
                // Scripts is not running
                if (!responseJSON.running) {
                    // Set as not-active
                    el.querySelector("[data-container=status-idle]").classList.remove("hidden");
                    el.querySelector("[data-container=status-busy]").classList.add("hidden");
                    el.dataset.running = null;

                    var reload = parseInt(el.dataset.reloadBackend) || 0;

                    // Inform user of user/reload or just success
                    if (responseJSON.error) {
                        top.TYPO3.Notification.error(ll("modal.header.error"), responseJSON.error);
                    } else if (reload > 0) {
                        top.TYPO3.Notification.success(ll("modal.header.success"), ll("message.reload"));
                        setTimeout(function () {
                            top.location.reload();
                        }, reload * 1000);
                    } else {
                        top.TYPO3.Notification.success(ll("modal.header.success"), ll("message.finished"));
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
        el.dataset.running = "1";
        el.querySelector("[data-container=status-idle]").classList.add("hidden");
        el.querySelector("[data-container=status-busy]").classList.remove("hidden");

        checkStatus(el);
    }

    // Traverse elements in
    function checkAllElementsState() {
        var els = document.querySelectorAll("[data-toggle=tx_runscript]"),
            completed = 0;

        function done() {
            completed++;

            if (completed >= els.length) {
                setTimeout(checkAllElementsState, 1000);
            }
        }

        els.forEach(function (el) {
            if (!el.dataset.running) {
                xhrGet("status", el.dataset.script, function (responseJSON) {
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
    document.querySelectorAll('[data-toggle=tx_runscript]').forEach((el) => {
        new RegularEvent('click', (event) => {
            event.preventDefault();

            if (el.dataset.running) {
                return;
            }

            xhrGet("start", el.dataset.script, (responseJSON) => {
                if (responseJSON.running) {
                    setActive(el);
                    top.TYPO3.Notification.info(ll("modal.header.running"), responseJSON.message);
                } else {
                    top.TYPO3.Notification.error(ll("modal.header.error"), responseJSON.error || responseJSON.message);
                }
            });
        }).bindTo(el);
    })
});
