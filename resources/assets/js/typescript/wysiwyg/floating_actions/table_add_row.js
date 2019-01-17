"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var TableAddRowAction = /** @class */ (function () {
    function TableAddRowAction() {
    }
    TableAddRowAction.prototype.create = function () {
        var _this = this;
        this.anchor = document.createElement('a');
        this.anchor.innerHTML = 'добавить строку';
        this.anchor.href = 'javascript:';
        this.anchor.addEventListener('click', function () {
            var currentTr = _this.element.parentElement;
            var tdsCount = currentTr.childElementCount;
            var newTr = document.createElement('tr');
            for (var i = 0; i < tdsCount; i++) {
                var newTd = document.createElement('td');
                newTr.appendChild(newTd);
            }
            currentTr.parentNode.insertBefore(newTr, currentTr.nextSibling);
        });
    };
    TableAddRowAction.prototype.getIdentifier = function () {
        return "table.add_row";
    };
    TableAddRowAction.prototype.getAnchor = function () {
        return this.anchor;
    };
    TableAddRowAction.prototype.getTargetElements = function () {
        return ['td', 'th'];
    };
    TableAddRowAction.prototype.setCurrentElement = function (element) {
        this.element = element;
    };
    TableAddRowAction.prototype.setEditor = function (editor) {
        this.editor = editor;
    };
    return TableAddRowAction;
}());
exports.TableAddRowAction = TableAddRowAction;
