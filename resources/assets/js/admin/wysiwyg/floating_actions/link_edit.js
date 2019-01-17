System.register(["./abstract-action"], function (exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var abstract_action_1, LinkEdit;
    return {
        setters: [
            function (abstract_action_1_1) {
                abstract_action_1 = abstract_action_1_1;
            }
        ],
        execute: function () {
            LinkEdit = class LinkEdit extends abstract_action_1.AbstractAction {
                create() {
                    this.anchor = document.createElement('a');
                    this.anchor.href = 'javascript:';
                    this.anchor.innerHTML = 'редактировать';
                    this.anchor.addEventListener('click', () => alert('Edit action is executing now'));
                }
                getIdentifier() {
                    return "link.edit";
                }
                getTargetElements() {
                    return ['a'];
                }
            };
            exports_1("LinkEdit", LinkEdit);
        }
    };
});
