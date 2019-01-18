export interface Action {

  getName(): string;

  getIcon(): string;

  getDescription(): string;

  performAction();

  prepare(): void;

  disable(): void;

  enable(): void;

  disabled(): boolean;

  shouldUpdateToolbarElement(current: HTMLElement): boolean;

  executeAction(): void;

  createToolbarElement(): HTMLDivElement;

  updateToolbarElement(): void;

  setEditor(editor): void;

  setToolbarElement(element: HTMLElement): void;

  setOnExecutedEvent(callable: any): Action;

}