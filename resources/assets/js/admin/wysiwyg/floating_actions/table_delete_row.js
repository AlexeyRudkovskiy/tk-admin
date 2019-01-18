System.register(["./abstract-action"], function (exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var abstract_action_1, TableDeleteRow;
    return {
        setters: [
            function (abstract_action_1_1) {
                abstract_action_1 = abstract_action_1_1;
            }
        ],
        execute: function () {
            TableDeleteRow = class TableDeleteRow extends abstract_action_1.AbstractAction {
                create() {
                    this.anchor = document.createElement('a');
                    this.anchor.innerHTML = 'удалить строку';
                    this.anchor.setAttribute('href', 'javascript:');
                    this.anchor.addEventListener('click', () => {
                        const tr = this.currentElement.parentElement;
                        const table = tr.parentElement;
                        table.removeChild(tr);
                        this.editor.hideFloatingActionPanel();
                    });
                }
                getIdentifier() {
                    return "table.delete_row";
                }
                getTargetElements() {
                    return ['td', 'th'];
                }
            };
            exports_1("TableDeleteRow", TableDeleteRow);
        }
    };
});
