<?php

namespace Telenok\Core\Support\File;

/**
 * @class Telenok.Core.Support.File.Processing
 * Class for processing file.
 */
class Processing
{
    /**
     * @protected
     * @static
     *
     * @property {Array} SAFE_EXTENSION
     * Safe extensions.
     * @member Telenok.Core.Support.File.Processing
     */
    const SAFE_EXTENSION = ['jpg', 'png', 'jpeg', 'gif', 'doc', 'txt', 'pdf', 'docx', 'xls', 'ppt'];

    /**
     * @protected
     * @static
     *
     * @property {Array} SAFE_MIME_TYPE
     * Safe mime types.
     * @member Telenok.Core.Support.File.Processing
     */
    const SAFE_MIME_TYPE = ['image/jpeg', 'image/pjpeg', 'image/gif', 'image/png',
        'application/msword', 'text/plain', 'application/pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', ];
}
