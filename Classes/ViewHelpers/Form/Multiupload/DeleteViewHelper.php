<?php

namespace Helhum\UploadExample\ViewHelpers\Form\Multiupload;
use Helhum\UploadExample\Property\TypeConverter\FileReferenceObjectStorageConverter;

/**
 * This class renders a checkbox that will remove the FileReference from the
 * associated ObjectStorage when unchecked. It may only be used inside of a
 * MultiUploadViewhelper.
 *
 * TODO: Find a better name for this class!
 */
class DeleteViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\CheckboxViewHelper {
	/**
	 * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
	 * @inject
	 */
	protected $hashService;
	
	public function render() {
		$checked = TRUE;
		if (!$this->arguments['value'] instanceof \TYPO3\CMS\Extbase\Domain\Model\FileReference) {
			var_dump($this->arguments['value']);
			throw new \InvalidArgumentException('The value assigned to Form.Multiupload.DeleteViewhelper must be of type FileReference', 1421848917);
		}		

		$resourcePointerValue = $this->arguments['value']->getUid();
		if ($resourcePointerValue === NULL) {
			// Newly created file reference which is not persisted yet.
			// Use the file UID instead, but prefix it with "file:" to communicate this to the type converter
			$resourcePointerValue = 'file:' . $this->arguments['value']->getOriginalResource()->getOriginalFile()->getUid();
		}
		$index = $this->viewHelperVariableContainer->get('Helhum\\UploadExample\\ViewHelpers\\Form\\MultiuploadViewHelper', 'fileReferenceIndex');
		$this->viewHelperVariableContainer->addOrUpdate('Helhum\\UploadExample\\ViewHelpers\\Form\\MultiuploadViewHelper', 'fileReferenceIndex', ++$index);
		// TODO: Fluid automatically adds the __identity key if the argument to the
		//		 viewhelper is a persisted model, but stripping the key on our own
		//		 is ugly here. Generate the name on ourselves?
		$name = $this->getName();
		$name = (strpos($name, '[__identity]') === FALSE ? $name : substr($name, 0, -strlen('[__identity]'))) . '[' . $index . ']';
		$this->registerFieldNameForFormTokenGeneration($name);
		$this->tag->addAttribute('name', $name . '[submittedFile][resourcePointer]');
		$this->tag->addAttribute('type', 'checkbox');
		$this->tag->addAttribute('value', htmlspecialchars($this->hashService->appendHmac((string) $resourcePointerValue)));
		if ($checked) {
			$this->tag->addAttribute('checked', 'checked');
		}
		return $this->tag->render();
	}
}