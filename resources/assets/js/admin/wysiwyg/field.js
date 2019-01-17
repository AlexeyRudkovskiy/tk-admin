System.register(["./editor"], function (exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var editor_1, WYSIWYGField;
    return {
        setters: [
            function (editor_1_1) {
                editor_1 = editor_1_1;
            }
        ],
        execute: function () {
            WYSIWYGField = class WYSIWYGField {
                constructor() {
                    this.wysiwygEditors = [];
                    this.editors = [];
                    this.wysiwygEditors = document.getElementsByClassName('wysiwyg-container');
                }
                render() {
                    for (let editor of this.wysiwygEditors) {
                        this.initEditor(editor);
                    }
                }
                initEditor(element) {
                    this.editors.push(new editor_1.Editor(element));
                }
            };
            exports_1("WYSIWYGField", WYSIWYGField);
        }
    };
});
