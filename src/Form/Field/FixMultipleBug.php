<?php
namespace Qs\Admin\Form\Field;

use OutOfBoundsException;

trait FixMultipleBug{

    /**
     * @return array
     */
    protected function initialPreviewConfig()
    {
        $files = $this->value ?: [];

        $config = [];

        foreach ($files as $index => $file) {
            $config[] = [
                'caption' => basename($file),
                'key'     => $file,
            ];
        }

        return $config;
    }

    /**
     * Destroy original files.
     *
     * @return string.
     */
    public function destroy($key)
    {
        $files = $this->original ?: [];

        if(!in_array($key, $files))
            throw new OutOfBoundsException(sprintf('Key %s does not exist.', $key));

        if ($this->storage->exists($key)) {
            $this->storage->delete($key);
        }

        $files = array_diff($files, [$key]);

        return array_values($files);
    }

}