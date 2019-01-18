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
var TableDeleteColumn = /** @class */ (function (_super) {
    __extends(TableDeleteColumn, _super);
    function TableDeleteColumn() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    TableDeleteColumn.prototype.create = function () {
        this.anchor = document.createElement('a');
        this.anchor.href = 'javascript:';
        this.anchor.innerHTML = 'удалить колонку';
        this.anchor.addEventListener('click', function () {
            console.log('delete column');
        });
    };
    TableDeleteColumn.prototype.getIdentifier = function () {
        return "table.delete_column";
    };
    TableDeleteColumn.prototype.getTargetElements = function () {
        return ['th'];
    };
    return TableDeleteColumn;
}(abstract_action_1.AbstractAction));
exports.TableDeleteColumn = TableDeleteColumn;
