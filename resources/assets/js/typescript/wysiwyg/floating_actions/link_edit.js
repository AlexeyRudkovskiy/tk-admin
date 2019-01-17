"use strict";
var __extends = (this && this.__extends) || (function () {
    var extendStatics = Object.setPrototypeOf ||
        ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
        function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
Object.defineProperty(exports, "__esModule", { value: true });
var abstract_action_1 = require("./abstract-action");
var LinkEdit = /** @class */ (function (_super) {
    __extends(LinkEdit, _super);
    function LinkEdit() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    LinkEdit.prototype.create = function () {
        this.anchor = document.createElement('a');
        this.anchor.href = 'javascript:';
        this.anchor.innerHTML = 'редактировать';
        this.anchor.addEventListener('click', function () { return alert('Edit action is executing now'); });
    };
    LinkEdit.prototype.getIdentifier = function () {
        return "link.edit";
    };
    LinkEdit.prototype.getTargetElements = function () {
        return ['a'];
    };
    return LinkEdit;
}(abstract_action_1.AbstractAction));
exports.LinkEdit = LinkEdit;
