System.register(["./abstract-action"], function (exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var abstract_action_1, TableAddColumn;
    return {
        setters: [
            function (abstract_action_1_1) {
                abstract_action_1 = abstract_action_1_1;
            }
        ],
        execute: function () {
            TableAddColumn = class TableAddColumn extends abstract_action_1.AbstractAction {
                create() {
                    this.anchor = document.createElement('a');
                    this.anchor.innerHTML = 'добавить колонку';
                    this.anchor.href = 'javascript:';
                    this.anchor.addEventListener('click', () => {
                        const currentTdIndex = [...this.currentElement.parentElement.children].indexOf(this.currentElement);
                        const table = this.currentElement.parentElement.parentElement.parentElement;
                        const tbody = table.getElementsByTagName('tbody')[0];
                        const th = document.createElement('th');
                        this.insertAfter(th, this.currentElement);
                        const trs = [...tbody.getElementsByTagName('tr')];
                        for (let tr of trs) {
                            const td = document.createElement('td');
                            this.insertAfter(td, tr.children[currentTdIndex]);
                            // console.log(td, tr, tr.children[currentTdIndex]);
                            // tr.parentElement.insertBefore(td, tr.children[currentTdIndex]);
                        }
                    });
                }
                getIdentifier() {
                    return "table.add_column";
                }
                getTargetElements() {
                    return ['th'];
                }
                insertAfter(newElement, currentElement) {
                    currentElement.parentNode.insertBefore(newElement, currentElement.nextSibling);
                }
            };
            exports_1("TableAddColumn", TableAddColumn);
        }
    };
});
