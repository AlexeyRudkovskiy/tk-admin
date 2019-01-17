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
var TableDeleteRow = /** @class */ (function (_super) {
    __extends(TableDeleteRow, _super);
    function TableDeleteRow() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    TableDeleteRow.prototype.create = function () {
        var _this = this;
        this.anchor = document.createElement('a');
        this.anchor.innerHTML = 'удалить строку';
        this.anchor.setAttribute('href', 'javascript:');
        this.anchor.addEventListener('click', function () {
            var tr = _this.currentElement.parentElement;
            var table = tr.parentElement;
            table.removeChild(tr);
            _this.editor.hideFloatingActionPanel();
        });
    };
    TableDeleteRow.prototype.getIdentifier = function () {
        return "table.delete_row";
    };
    TableDeleteRow.prototype.getTargetElements = function () {
        return ['td', 'th'];
    };
    return TableDeleteRow;
}(abstract_action_1.AbstractAction));
exports.TableDeleteRow = TableDeleteRow;
