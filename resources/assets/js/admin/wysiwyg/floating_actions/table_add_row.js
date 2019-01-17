System.register([], function (exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var TableAddRowAction;
    return {
        setters: [],
        execute: function () {
            TableAddRowAction = class TableAddRowAction {
                create() {
                    this.anchor = document.createElement('a');
                    this.anchor.innerHTML = 'добавить строку';
                    this.anchor.href = 'javascript:';
                    this.anchor.addEventListener('click', () => {
                        const currentTr = this.element.parentElement;
                        const tdsCount = currentTr.childElementCount;
                        const newTr = document.createElement('tr');
                        for (let i = 0; i < tdsCount; i++) {
                            const newTd = document.createElement('td');
                            newTr.appendChild(newTd);
                        }
                        currentTr.parentNode.insertBefore(newTr, currentTr.nextSibling);
                    });
                }
                getIdentifier() {
                    return "table.add_row";
                }
                getAnchor() {
                    return this.anchor;
                }
                getTargetElements() {
                    return ['td', 'th'];
                }
                setCurrentElement(element) {
                    this.element = element;
                }
                setEditor(editor) {
                    this.editor = editor;
                }
            };
            exports_1("TableAddRowAction", TableAddRowAction);
        }
    };
});
