"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
var AbstractAction = /** @class */ (function () {
    function AbstractAction() {
    }
    AbstractAction.prototype.getAnchor = function () {
        return this.anchor;
    };
    AbstractAction.prototype.setCurrentElement = function (element) {
        this.currentElement = element;
    };
    AbstractAction.prototype.setEditor = function (editor) {
        this.editor = editor;
    };
    return AbstractAction;
}());
exports.AbstractAction = AbstractAction;
