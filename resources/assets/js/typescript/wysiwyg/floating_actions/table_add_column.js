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
var TableAddColumn = /** @class */ (function (_super) {
    __extends(TableAddColumn, _super);
    function TableAddColumn() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    TableAddColumn.prototype.create = function () {
        var _this = this;
        this.anchor = document.createElement('a');
        this.anchor.innerHTML = 'добавить колонку';
        this.anchor.href = 'javascript:';
        this.anchor.addEventListener('click', function () {
            var currentTdIndex = _this.currentElement.parentElement.children.slice().indexOf(_this.currentElement);
            var table = _this.currentElement.parentElement.parentElement.parentElement;
            var tbody = table.getElementsByTagName('tbody')[0];
            var th = document.createElement('th');
            _this.insertAfter(th, _this.currentElement);
            var trs = tbody.getElementsByTagName('tr').slice();
            for (var _i = 0, trs_1 = trs; _i < trs_1.length; _i++) {
                var tr = trs_1[_i];
                var td = document.createElement('td');
                _this.insertAfter(td, tr.children[currentTdIndex]);
            }
        });
    };
    TableAddColumn.prototype.getIdentifier = function () {
        return "table.add_column";
    };
    TableAddColumn.prototype.getTargetElements = function () {
        return ['th'];
    };
    TableAddColumn.prototype.insertAfter = function (newElement, currentElement) {
        currentElement.parentNode.insertBefore(newElement, currentElement.nextSibling);
    };
    return TableAddColumn;
}(abstract_action_1.AbstractAction));
exports.TableAddColumn = TableAddColumn;
