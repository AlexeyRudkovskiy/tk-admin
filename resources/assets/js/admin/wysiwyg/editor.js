System.register(["./floating_actions/table_add_row", "./floating_actions/table_add_column", "./floating_actions/table_delete_row", "./floating_actions/table-delete-column", "./floating_actions/link_edit", "./toolbar-actions/action-bold"], function (exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var table_add_row_1, table_add_column_1, table_delete_row_1, table_delete_column_1, link_edit_1, action_bold_1, Editor;
    return {
        setters: [
            function (table_add_row_1_1) {
                table_add_row_1 = table_add_row_1_1;
            },
            function (table_add_column_1_1) {
                table_add_column_1 = table_add_column_1_1;
            },
            function (table_delete_row_1_1) {
                table_delete_row_1 = table_delete_row_1_1;
            },
            function (table_delete_column_1_1) {
                table_delete_column_1 = table_delete_column_1_1;
            },
            function (link_edit_1_1) {
                link_edit_1 = link_edit_1_1;
            },
            function (action_bold_1_1) {
                action_bold_1 = action_bold_1_1;
            }
        ],
        execute: function () {
            Editor = class Editor {
                constructor(editor) {
                    this.editor = editor;
                    this.floatingActions = [];
                    this.toolbarActions = [];
                    this.toolbarSegments = [['toolbar.bold']];
                    this.tableTagNames = ['td', 'th'];
                    this.currentElement = null;
                    this.toolbarContainer = null;
                    this.savedSelection = null;
                    this.editorContent = null;
                    this.hotKeys = [];
                    document.execCommand('defaultParagraphSeparator', false, 'p');
                    this.toolbarContainer = editor.querySelector('.wysiwyg-toolbar');
                    this.floatingActions.push(new table_add_row_1.TableAddRowAction());
                    this.floatingActions.push(new table_delete_row_1.TableDeleteRow());
                    this.floatingActions.push(new table_add_column_1.TableAddColumn());
                    this.floatingActions.push(new table_delete_column_1.TableDeleteColumn());
                    this.floatingActions.push(new link_edit_1.LinkEdit());
                    this.toolbarActions.push(new action_bold_1.ActionBold());
                    this.editorContent = editor.querySelector('.wysiwyg-content');
                    this.floatingPanel = editor.querySelector('.wysiwyg-floating-panel');
                    this.editorContent.addEventListener('keyup', () => this.detectCurrentObject());
                    this.editorContent.addEventListener('click', () => this.detectCurrentObject());
                    this.editorContent.addEventListener('blur', () => {
                        let selection = window.getSelection();
                        if (selection.rangeCount > 0) {
                            this.savedSelection = selection.getRangeAt(0);
                        }
                    });
                    this.createFloatingActions();
                    this.createToolbarActions();
                }
                hideFloatingActionPanel() {
                    this.floatingPanel.classList.add('hidden');
                }
                detectCurrentObject() {
                    const currentObject = window.getSelection().getRangeAt(0).commonAncestorContainer;
                    const object = this.findParent(currentObject);
                    const offsets = this.calculateOffsets(object);
                    this.currentElement = object;
                    let tagName = object.tagName.toLowerCase();
                    const actions = this.floatingActions
                        .filter(item => item.getTargetElements().indexOf(tagName) > -1);
                    if (offsets.left > 0 && offsets.top > 0 && actions.length > 0) {
                        this.floatingPanel.classList.remove('hidden');
                        window.setTimeout(() => {
                            this.floatingPanel.style.left = offsets.left + 'px';
                            this.floatingPanel.style.top = (offsets.top - this.floatingPanel.offsetHeight - 6) + 'px';
                        }, 1);
                        this.floatingPanel.innerText = '';
                        actions
                            .map(item => {
                            item.setCurrentElement(this.currentElement);
                            return item;
                        })
                            .map(item => item.getAnchor())
                            .forEach(item => this.floatingPanel.appendChild(item));
                    }
                    else {
                        this.floatingPanel.classList.add('hidden');
                    }
                }
                calculateOffsets(object) {
                    let offsets = { left: 0, top: 0 };
                    if (object.tagName.toLowerCase() == 'th' || object.tagName.toLowerCase() == 'td') {
                        let tableObject = this.findTableOffset(object);
                        offsets.left = tableObject.offsetLeft;
                        offsets.top = tableObject.offsetTop;
                    }
                    offsets.left += object.offsetLeft;
                    offsets.top += object.offsetTop;
                    return offsets;
                }
                findParent(object) {
                    if (typeof object.offsetTop === "undefined") {
                        object = object.parentElement;
                    }
                    return object;
                }
                findTableOffset(object) {
                    if (object.tagName.toLowerCase() !== 'table') {
                        return this.findTableOffset(object.parentElement);
                    }
                    return object;
                }
                createFloatingActions() {
                    this.floatingActions
                        .forEach(item => {
                        item.setEditor(this);
                        item.create();
                    });
                }
                createToolbarActions() {
                    this.toolbarActions
                        .forEach(action => {
                        action.setEditor(this);
                        action.prepare();
                    });
                    this.toolbarSegments
                        .map((segment) => {
                        return segment
                            .map(item => {
                            return this.toolbarActions
                                .filter(action => action.getName() === item)[0];
                        });
                    })
                        .forEach(segment => {
                        const segmentContainer = document.createElement('div');
                        segmentContainer.classList.add('wysiwyg-toolbar-section');
                        segment
                            .forEach(action => {
                            const item = document.createElement('div');
                            const itemIcon = document.createElement('i');
                            item.classList.add('wysiwyg-toolbar-item');
                            action.getIcon().split(' ')
                                .forEach(itemIconClass => itemIcon.classList.add(itemIconClass));
                            item.addEventListener('click', action.executeAction.bind(action));
                            item.appendChild(itemIcon);
                            segmentContainer.appendChild(item);
                        });
                        this.toolbarContainer.appendChild(segmentContainer);
                    });
                }
                restoreSelection() {
                    this.editorContent.focus();
                    if (this.savedSelection !== null) {
                        if (window.getSelection) //non IE and there is already a selection
                         {
                            const s = window.getSelection();
                            if (s.rangeCount > 0)
                                s.removeAllRanges();
                            s.addRange(this.savedSelection);
                        }
                        else if (document.createRange) //non IE and no selection
                         {
                            window.getSelection().addRange(this.savedSelection);
                        }
                    }
                }
                addHotKey(hotkey, action) {
                    this.hotKeys.push({ hotkey, action });
                    console.log(this.hotKeys);
                }
            };
            exports_1("Editor", Editor);
        }
    };
});
