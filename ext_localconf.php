<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Helhum.' . $_EXTKEY,
    'Piexample',
    [
        'Example' => 'list, show, new, create, edit, update, delete',
    ],
    // non-cacheable actions
    [
        'Example' => 'list, show, new, create, edit, update, delete',
    ]
);

if (version_compare(TYPO3_branch, '7.0', '>')) {
    if (TYPO3_MODE === 'BE') {
        $icons = [
            'ext-upload-icon' => 'Extension.png',
        ];
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        foreach ($icons as $identifier => $path) {
            $iconRegistry->registerIcon(
                $identifier,
                \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
                ['source' => 'EXT:upload_example/Resources/Public/Icons/' . $path]
            );
        }
    }
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('Helhum\\UploadExample\\Property\\TypeConverter\\UploadedFileReferenceConverter');
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter('Helhum\\UploadExample\\Property\\TypeConverter\\ObjectStorageConverter');
