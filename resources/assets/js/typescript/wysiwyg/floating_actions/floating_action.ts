export interface FloatingAction {

  create();

  getIdentifier(): string;

  getAnchor(): HTMLAnchorElement;

  getTargetElements(): string[];

  setCurrentElement(element);

  setEditor(editor: any);

  shouldBeDisplayed(element: HTMLElement): boolean;

}