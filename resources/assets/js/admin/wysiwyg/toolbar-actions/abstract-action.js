System.register([], function (exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var AbstractAction;
    return {
        setters: [],
        execute: function () {
            AbstractAction = class AbstractAction {
                constructor() {
                    this.editor = null;
                }
                executeAction() {
                    if (/AppleWebKit/.test(navigator.userAgent) || /Google Inc/.test(navigator.vendor)) {
                        this.editor.restoreSelection();
                    }
                    this.performAction();
                }
                setEditor(editor) {
                    this.editor = editor;
                }
            };
            exports_1("AbstractAction", AbstractAction);
        }
    };
});
