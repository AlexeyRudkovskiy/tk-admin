System.register([], function (exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var AbstractAction;
    return {
        setters: [],
        execute: function () {
            AbstractAction = class AbstractAction {
                getAnchor() {
                    return this.anchor;
                }
                setCurrentElement(element) {
                    this.currentElement = element;
                }
                setEditor(editor) {
                    this.editor = editor;
                }
            };
            exports_1("AbstractAction", AbstractAction);
        }
    };
});
