///<reference path="toolbar-actions/action-gallery.ts"/>
import {TableAddRowAction} from "./floating_actions/table_add_row";
import {FloatingAction} from "./floating_actions/floating_action";
import {TableAddColumn} from "./floating_actions/table_add_column";
import {TableDeleteRow} from "./floating_actions/table_delete_row";
import {TableDeleteColumn} from "./floating_actions/table-delete-column";
import {LinkEdit} from "./floating_actions/link_edit";
import {Action} from "./toolbar-actions/action";
import {ActionBold} from "./toolbar-actions/action-bold";
import hotkeys from 'hotkeys-js';
import {ActionItalic} from "./toolbar-actions/action-italic";
import {ActionMedia} from "./toolbar-actions/action-media";
import {ImageChange} from "./floating_actions/image-change";
import {ImageDelete} from "./floating_actions/image-delete";
import {ImageEdit} from "./floating_actions/image-edit";
import {ActionUnderline} from "./toolbar-actions/action-underline";
import {ActionTextAlignLeft} from "./toolbar-actions/action-text-align-left";
import {ActionTextAlignRight} from "./toolbar-actions/action-text-align-right";
import {ActionTextAlignCenter} from "./toolbar-actions/action-text-align-center";
import {ActionTextAlignJustify} from "./toolbar-actions/action-text-align-justify";
import {ActionListOrdered} from "./toolbar-actions/action-list-ordered";
import {ActionListUnordered} from "./toolbar-actions/action-list-unordered";
import {ActionReadMore} from "./toolbar-actions/action-read-more";
import {ActionListIndent} from "./toolbar-actions/action-list-indent";
import {ActionListOutdent} from "./toolbar-actions/action-list-outdent";
import {ActionSourceCode} from "./toolbar-actions/action-source-code";
import {ActionLinkCreate} from "./toolbar-actions/action-link-create";
import {ActionVideo} from "./toolbar-actions/action-video";
import {createUploader} from "./uploader-placeholder";
import {Gallery} from "./gallery";
import {GalleryDelete} from "./floating_actions/gallery-delete";
import {GalleryDeleteItem} from "./floating_actions/gallery-delete-item";
import {GalleryAdd} from "./floating_actions/gallery-add";
import {ActionTable} from "./toolbar-actions/action-table";
import {TableDelete} from "./floating_actions/table-delete";
import {ActionGallery} from "./toolbar-actions/action-gallery";

export class Editor {

  private floatingPanel: HTMLDivElement;

  private floatingActions:FloatingAction[] = [];

  private toolbarActions:Action[] = [];

  private toolbarSegments: any[] = [
    [ 'toolbar.bold', 'toolbar.italic', 'toolbar.underline' ],
    [ 'toolbar.text_align.left', 'toolbar.text_align.center', 'toolbar.text_align.right', 'toolbar.text_align.justify' ],
    [ 'toolbar.list.ordered', 'toolbar.list.unordered', 'toolbar.list.indent', 'toolbar.list.outdent' ],
    [ 'toolbar.table' ],
    [ 'toolbar.link.create' ],
    [ 'toolbar.read_more' ],
    [ 'toolbar.media', 'toolbar.video', 'toolbar.gallery' ],
    [ 'toolbar.source_code' ]
  ];

  private tableTagNames = [ 'td', 'th' ];

  private currentElement = null;

  private toolbarContainer: HTMLDivElement = null;

  private savedSelection: any = null;

  private editorContent: HTMLDivElement = null;

  private hotKeys: any[] = [];

  private schema: any = [];

  private textarea: HTMLTextAreaElement = null;

  public constructor(private editor: HTMLElement, private id: string) {
    document.execCommand('defaultParagraphSeparator', false, 'p');

    this.toolbarContainer = editor.querySelector('.wysiwyg-toolbar') as HTMLDivElement;

    this.floatingActions.push(new TableDelete());
    this.floatingActions.push(new TableAddRowAction());
    this.floatingActions.push(new TableDeleteRow());
    this.floatingActions.push(new TableAddColumn());
    this.floatingActions.push(new TableDeleteColumn());
    this.floatingActions.push(new LinkEdit());
    this.floatingActions.push(new ImageChange());
    this.floatingActions.push(new ImageDelete());
    this.floatingActions.push(new ImageEdit());
    this.floatingActions.push(new GalleryDelete());
    this.floatingActions.push(new GalleryDeleteItem());
    this.floatingActions.push(new GalleryAdd());

    this.toolbarActions.push(new ActionBold());
    this.toolbarActions.push(new ActionItalic());
    this.toolbarActions.push(new ActionUnderline());
    this.toolbarActions.push(new ActionTextAlignLeft());
    this.toolbarActions.push(new ActionTextAlignRight());
    this.toolbarActions.push(new ActionTextAlignCenter());
    this.toolbarActions.push(new ActionTextAlignJustify());
    this.toolbarActions.push(new ActionListOrdered());
    this.toolbarActions.push(new ActionListUnordered());
    this.toolbarActions.push(new ActionListIndent());
    this.toolbarActions.push(new ActionListOutdent());
    this.toolbarActions.push(new ActionReadMore());
    this.toolbarActions.push(new ActionMedia());
    this.toolbarActions.push(new ActionSourceCode());
    this.toolbarActions.push(new ActionLinkCreate());
    this.toolbarActions.push(new ActionVideo());
    this.toolbarActions.push(new ActionTable());
    this.toolbarActions.push(new ActionGallery());

    this.editorContent = editor.querySelector('.wysiwyg-content') as HTMLDivElement;
    this.floatingPanel = editor.querySelector('.wysiwyg-floating-panel') as HTMLDivElement;

    editor.querySelector('.wysiwyg-source-code-editor').setAttribute('id', this.identifier);

    this.editorContent.addEventListener('keyup', () => this.detectCurrentObject(false, null));
    this.editorContent.addEventListener('click', (e) => this.detectCurrentObject(true,e));
    this.editorContent.addEventListener('blur', () => {
      let selection = (window as any).getSelection();
      if (selection.rangeCount > 0) {
        this.savedSelection = selection.getRangeAt(0);
      }

      this.saveContent();
    });

    this.createFloatingActions();
    this.createToolbarActions();
    this.createTextArea();

    this.setupDragAndDrop();

    this.createGalleries();

    const tables = document.querySelectorAll('table');
    for (let i = 0; i < tables.length; i++) {
      (tables[i] as any).setAttribute('contenteditable', 'true');
    }
  }

  private createGalleries() {
    const galleries = this.editorContent.querySelectorAll('.post-gallery');
    for (let i = 0; i < galleries.length; i++) {
      const gallery = new Gallery(galleries[i] as HTMLDivElement);
    }
  }

  public get content() {
    return this.editorContent.innerHTML;
  }

  public set content(value: string) {
    this.editorContent.innerHTML = value;
    this.textarea.innerHTML = value;

    const tables = document.querySelectorAll('table');
    for (let i = 0; i < tables.length; i++) {
      (tables[i] as any).setAttribute('contenteditable', 'true');
    }

    this.createGalleries();
  }

  public get identifier() {
    return this.id;
  }

  public get container() {
    return this.editor;
  }

  public get selection() {
    if (this.savedSelection !== null) {
      return this.savedSelection;
    }

    let selections = (window as any).getSelection();
    if (selections.rangeCount > 0) {
      return selections.getRangeAt(0);
    }

    return null;
  }

  public get toolbarItems() {
    return this.toolbarActions;
  }

  public saveContent() {
    const editableTables = this.editorContent.querySelectorAll('table[contenteditable]');
    for (let i = 0; i < editableTables.length; i++) {
      (editableTables[i] as any).removeAttribute('contenteditable');
    }
    this.textarea.innerHTML = this.content;
  }

  public hideFloatingActionPanel() {
    this.floatingPanel.classList.add('hidden');
  }

  private detectCurrentObject(isClick: boolean, e:any) {
    let currentObject = null;
    if (!isClick) {
      currentObject = (window as any).getSelection().getRangeAt(0).commonAncestorContainer;
    } else {
      currentObject = e.target;
      if (currentObject.tagName.toLowerCase() == 'div') {
        currentObject = currentObject.firstElementChild;
      }
    }

    if (currentObject === null && isClick) {
      currentObject = this.editorContent.querySelector('[contenteditable="false"]');
    }

    const object = this.findParent(currentObject);
    const offsets = this.calculateOffsets(object);

    this.saveSelection();
    this.currentElement = object;

    this.updateToolbarActions();

    let tagName = object.tagName.toLowerCase();

    const actions = this.floatingActions
      .filter(item => {
        return item.getTargetElements().indexOf(tagName) > -1 || item.shouldBeDisplayed(this.currentElement)
      });

    if (offsets.left > 0 && offsets.top > 0 && actions.length > 0) {
      this.floatingPanel.classList.remove('hidden');
      (window as any).setTimeout(() => {
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

    } else {
      this.floatingPanel.classList.add('hidden');
    }
  }

  private calculateOffsets(object: any) {
    let offsets = { left: 0, top: 0 };

    if (object.tagName.toLowerCase() == 'th' || object.tagName.toLowerCase() == 'td') {
      let tableObject: any = this.findTableOffset(object);
      offsets.left = tableObject.offsetLeft;
      offsets.top = tableObject.offsetTop;
    }

    offsets.left += object.offsetLeft;
    offsets.top += object.offsetTop;

    return offsets;
  }

  private findParent(object: any) {
    if (object === null) {
      return null;
    }
    if (typeof object.offsetTop === "undefined") {
      object = (object as any).parentElement;
    }

    if (object.parentElement.classList.contains('gallery-item')) {
      object = (object as any).parentElement;
    }

    return object;
  }

  private findTableOffset(object: any): any {
    if (object.tagName.toLowerCase() !== 'table') {
      return this.findTableOffset(object.parentElement);
    }
    return object;
  }

  private createFloatingActions() {
    this.floatingActions
      .forEach(item => {
        item.setEditor(this);
        item.create();
      });
  }

  private updateToolbarActions() {
    this.toolbarActions
      .filter(action => action.shouldUpdateToolbarElement(this.currentElement))
      .forEach(action => action.updateToolbarElement());
  }

  private createToolbarActions() {
    this.toolbarActions
      .forEach(action => {
        action.setEditor(this);
        action.prepare();
      });

    this.toolbarSegments
      .map((segment:string[]) => {
        return segment
          .map(item => {
            return this.toolbarActions
              .filter(action => action.getName() === item)[0]
          });
      })
      .forEach(segment => {
        const segmentContainer = document.createElement('div');
        segmentContainer.classList.add('wysiwyg-toolbar-section');

        (segment as Action[])
          .forEach(action => {
            const item = action.createToolbarElement();
            action.setToolbarElement(item);
            action.setOnExecutedEvent(() => this.updateToolbarActions());
            segmentContainer.appendChild(item);
          });

        this.toolbarContainer.appendChild(segmentContainer);
      });
  }

  public restoreSelection() {
    this.editorContent.focus();
    if (this.savedSelection !== null) {
      if ((window as any).getSelection)//non IE and there is already a selection
      {
        const s = (window as any).getSelection();
        if (s.rangeCount > 0)
          s.removeAllRanges();
        s.addRange(this.savedSelection);
      }
      else if (document.createRange)//non IE and no selection
      {
        (window as any).getSelection().addRange(this.savedSelection);
      }
    }
  }

  public saveSelection() {
    const selections = (window as any).getSelection();
    if (selections.rangeCount > 0) {
      this.savedSelection = selections.getRangeAt(0);
    }
  }

  public addHotKey(hotkey, action: Action) {
    hotkeys(hotkey, (e) => {
      e.preventDefault();
      const selection = (window as any).getSelection();
      if (selection.rangeCount > 0) {
        this.savedSelection = selection.getRangeAt(0);
      }
      action.executeAction();
    });
  }

  private createTextArea() {
    this.textarea = document.createElement('textarea');
    this.textarea.setAttribute('name', this.editor.getAttribute('data-name'));
    this.textarea.setAttribute('id', this.id);
    this.textarea.classList.add('hidden');
    this.editor.appendChild(this.textarea);
  }

  private setupDragAndDrop() {
    this.editorContent.addEventListener('dragover', (e) => {
      // e.preventDefault();
    });

    this.editorContent.addEventListener('drop', (e) => {
      e.preventDefault();
      this.restoreSelection();

      if (e.dataTransfer.files.length < 1) {
        return;
      }

      const x = e.clientX;
      const y = e.clientY;

      const node = document.createElement('div');
      node.setAttribute('contenteditable', 'false');

      let positionParent = null;

      if ((document as any).caretPositionFromPoint) {
        const pos = (document as any).caretPositionFromPoint(x, y).offsetNode;
        positionParent = pos;
        // let range = document.createRange();
        // range.setStart(pos.offsetNode, pos.offset);
        // range.collapse();
        // range.insertNode(node);
      }

      if ((document as any).caretRangeFromPoint) {
        positionParent = (document as any).caretRangeFromPoint(x, y).commonAncestorContainer;
        // let range = document.createRange();
        //
        // pos.collapse();
        // pos.insertNode(node);
      }

      while (positionParent.tagName !== 'P') {
        positionParent = positionParent.parentElement;
      }

      positionParent.parentElement.insertBefore(node, positionParent.nextSibling);

      createUploader(node, e.dataTransfer.files).createPlaceholder();

    });
  }

}