System.register(["./abstract-action"], function (exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var abstract_action_1, ActionBold;
    return {
        setters: [
            function (abstract_action_1_1) {
                abstract_action_1 = abstract_action_1_1;
            }
        ],
        execute: function () {
            ActionBold = class ActionBold extends abstract_action_1.AbstractAction {
                getName() {
                    return "toolbar.bold";
                }
                getDescription() {
                    return "Make text bold";
                }
                getIcon() {
                    return "fa fa-bold";
                }
                prepare() {
                    this.editor.addHotKey('Ctrl+B', this);
                }
                performAction() {
                    document.execCommand('bold', false, null);
                    // document.queryCommandState('bold');
                }
            };
            exports_1("ActionBold", ActionBold);
        }
    };
});
