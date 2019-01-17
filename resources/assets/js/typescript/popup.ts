export enum PopupSize {
  SMALL, MEDIUM, LARGE, FULLPAGE
}

export class Popup {

  protected popupContainer: HTMLDivElement = null;
  protected popupContentContainer: HTMLDivElement = null;
  protected popupHeaderLabel: HTMLDivElement = null;
  protected popupContent: HTMLDivElement = null;
  protected closeIcon: HTMLDivElement = null;

  public constructor(create?:boolean) {
    if (!create || typeof create === "undefined") {
      this.popupContainer = <HTMLDivElement>document.querySelector('.popup-container');
      this.popupContentContainer = <HTMLDivElement>this.popupContainer.querySelector('.popup-content-container');
      this.popupHeaderLabel = <HTMLDivElement>this.popupContentContainer.querySelector('.popup-header-label');
      this.closeIcon = <HTMLDivElement>this.popupContentContainer.querySelector('.popup-close');
      this.popupContent = <HTMLDivElement>this.popupContentContainer.querySelector('.popup-content');
    } else {
      this.createPopup();
    }

    this.closeIcon.addEventListener('click', () => this.hide());
  }

  set size(size: PopupSize) {
    this.popupContentContainer.classList.add(this.getSizeClass(size));
    if (size === PopupSize.FULLPAGE) {
      this.popupContainer.classList.add('fullpage');
    } else {
      if (this.popupContainer.classList.contains('fullpage')) {
        this.popupContainer.classList.remove('fullpage');
      }
    }
  }

  set header(label: string) {
    this.popupHeaderLabel.innerHTML = label;
  }

  get content(): HTMLDivElement {
    return this.popupContent;
  }

  public show() {
    this.preparePopup();
    this.popupContainer.classList.remove('hidden');
  }

  public hide() {
    this.popupContainer.classList.add('hidden');
  }

  clearContent() {
    while (this.popupContent.firstElementChild) {
      this.popupContent.removeChild(this.popupContent.firstElementChild);
    }
  }

  protected getSizeClass(size: PopupSize): string {
    if (this.popupContentContainer.classList.contains('popup-small'))
      this.popupContentContainer.classList.remove('popup-small');

    if (this.popupContentContainer.classList.contains('popup-medium'))
      this.popupContentContainer.classList.remove('popup-medium');

    if (this.popupContentContainer.classList.contains('popup-large'))
      this.popupContentContainer.classList.remove('popup-large');

    if (this.popupContentContainer.classList.contains('popup-fullpage'))
      this.popupContentContainer.classList.remove('popup-fullpage');

    if (size == PopupSize.SMALL) return 'popup-small';
    if (size == PopupSize.MEDIUM) return 'popup-medium';
    if (size == PopupSize.LARGE) return 'popup-large';
    if (size == PopupSize.FULLPAGE) return 'popup-fullpage';

    return null;
  }

  protected createPopup() {
    this.popupContainer = document.createElement('div');
    this.popupContentContainer = document.createElement('div');
    this.popupHeaderLabel = document.createElement('div');
    this.closeIcon = document.createElement('div');
    this.popupContent = document.createElement('div');

    this.popupContainer.classList.add('popup-container', 'hidden');
    this.popupContentContainer.classList.add('popup-content-container');
    this.popupHeaderLabel.classList.add('popup-header-label');
    this.closeIcon.classList.add('popup-close');
    this.popupContent.classList.add('popup-content');

    const popupHeaderContainer = document.createElement('div');
    const closeIcon = document.createElement('i');
    popupHeaderContainer.classList.add('popup-header');
    closeIcon.classList.add('fa', 'fa-close');

    this.popupContainer.appendChild(this.popupContentContainer);

    this.popupContentContainer.appendChild(popupHeaderContainer);
    this.popupContentContainer.appendChild(this.popupContent);

    popupHeaderContainer.appendChild(this.popupHeaderLabel);
    popupHeaderContainer.appendChild(this.closeIcon);

    this.closeIcon.appendChild(closeIcon);

    document.body.appendChild(this.popupContainer);
  }

  protected preparePopup() {
    /// empty
  }

}