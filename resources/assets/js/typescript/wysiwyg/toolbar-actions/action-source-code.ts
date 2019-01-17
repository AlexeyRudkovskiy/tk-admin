import {AbstractAction} from "./abstract-action";
import * as ace from 'ace-builds'

export enum EditorType {
  WYSIWYG, SOURCE_CODE
}

export class ActionSourceCode extends AbstractAction {

  private sourceCodeEditor: any = null;

  private editorContainer: HTMLDivElement = null;

  private editorContent: HTMLDivElement = null;

  private sourceCodeEditorContent: HTMLDivElement = null;

  private currentEditorMode: EditorType = EditorType.WYSIWYG;

  private beautify: any = null;

  getDescription(): string {
    return "Відкрити вихідний код";
  }

  getIcon(): string {
    return "fa fa-code";
  }

  getName(): string {
    return "toolbar.source_code";
  }

  performAction() {
    if (this.sourceCodeEditor === null) {
      this.beautify = (window as any).html_beautify;

      ace.config.set('basePath', '/arudkovskiy/admin/js/lib/ace');

      this.sourceCodeEditor = ace.edit(this.editor.id, {
        maxLines: Infinity,
        theme: 'ace/theme/dreamweaver',
        mode: 'ace/mode/html'
      });

      this.editorContainer = this.editor.container;
      this.editorContent = this.editorContainer.querySelector('.wysiwyg-content') as HTMLDivElement;
      this.sourceCodeEditorContent = this.editorContainer.querySelector('.wysiwyg-source-code-editor') as HTMLDivElement;
    }

    if (this.currentEditorMode === EditorType.WYSIWYG) {
      this.editorContent.classList.add('hidden');
      this.sourceCodeEditorContent.classList.remove('hidden');

      const content = this.beautify(this.editor.content);
      this.sourceCodeEditor.session.setValue(content);
      this.editor.hideFloatingActionPanel();

      this.currentEditorMode = EditorType.SOURCE_CODE;
    } else {
      this.editorContent.classList.remove('hidden');
      this.sourceCodeEditorContent.classList.add('hidden');

      const content = this.sourceCodeEditor.getValue();

      this.currentEditorMode = EditorType.WYSIWYG;
      this.editor.content = content;
    }

    let shouldBeDisabled = this.currentEditorMode === EditorType.SOURCE_CODE;
    this.editor.toolbarItems
      .filter(item => item !== this)
      .forEach(item => shouldBeDisabled ? item.disable() : item.enable());

  }

  prepare(): void {
    this.editor.addHotKey(this.composeHotKey('shift+c'), this);
  }

  createToolbarElement(): HTMLDivElement {
    return this.createSimpleToolbarElement();
  }

  shouldUpdateToolbarElement(current: HTMLElement): boolean {
    return false;
  }

  updateToolbarElement(): void {
  }

}