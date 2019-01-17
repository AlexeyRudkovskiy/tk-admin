import {uploadFile} from "./upload-file";
import {fileType} from "./modules/file-type";
import Translation from "./modules/translation";
import {toggleBoolean} from "./modules/toggle-boolean";
import {confirmDeleteAction} from "./modules/confirm-delete";


export default {
  onetime: [
    fileType,
    toggleBoolean,
    Translation.setup,
    confirmDeleteAction
  ],
  async: {
    any: [
      Translation.translateAutomatically
    ],
    files: [
      uploadFile
    ]
  }
};