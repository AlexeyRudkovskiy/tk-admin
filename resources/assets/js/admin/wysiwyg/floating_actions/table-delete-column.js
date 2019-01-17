System.register(["./abstract-action"], function (exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var abstract_action_1, TableDeleteColumn;
    return {
        setters: [
            function (abstract_action_1_1) {
                abstract_action_1 = abstract_action_1_1;
            }
        ],
        execute: function () {
            TableDeleteColumn = class TableDeleteColumn extends abstract_action_1.AbstractAction {
                create() {
                    this.anchor = document.createElement('a');
                    this.anchor.href = 'javascript:';
                    this.anchor.innerHTML = 'удалить колонку';
                    this.anchor.addEventListener('click', () => {
                        console.log('delete column');
                    });
                }
                getIdentifier() {
                    return "table.delete_column";
                }
                getTargetElements() {
                    return ['th'];
                }
            };
            exports_1("TableDeleteColumn", TableDeleteColumn);
        }
    };
});
