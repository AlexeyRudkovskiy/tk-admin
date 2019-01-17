System.register(["./wysiwyg/field"], function (exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var field_1;
    return {
        setters: [
            function (field_1_1) {
                field_1 = field_1_1;
            }
        ],
        execute: function () {
            (function () {
                const fields = [
                    new field_1.WYSIWYGField()
                ];
                for (let field of fields) {
                    field.render();
                }
            })();
        }
    };
});
