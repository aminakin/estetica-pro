<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if (!function_exists('getSliderForItem'))
{
	function getSliderForItem(&$item, $propertyCode, $addDetailToSlider)
	{
		$result = array();

		if (!empty($item) && is_array($item))
		{
			if (
				'' != $propertyCode &&
				isset($item['PROPERTIES'][$propertyCode]) &&
				'F' == $item['PROPERTIES'][$propertyCode]['PROPERTY_TYPE']
			)
			{
				if ('MORE_PHOTO' == $propertyCode && isset($item['MORE_PHOTO']) && !empty($item['MORE_PHOTO']))
				{
					foreach ($item['MORE_PHOTO'] as &$onePhoto)
					{
                        $file = CFile::ResizeImageGet(
                            $onePhoto['ID'],
                            array('width' => 400, 'height' => 410),
                            BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                            false
                        );
                        $file_small = CFile::ResizeImageGet(
                            $onePhoto['ID'],
                            array('width' => 90, 'height' => 90),
                            BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                            false
                        );
						$result[] = array(
							'ID' => intval($onePhoto['ID']),
							'SRC' => $file['src'],
							'SRC_BIG' => $onePhoto['SRC'],
							'SRC_SMALL' => $file_small['src'],
							'WIDTH' => intval($file['width']),
							'HEIGHT' => intval($file['height'])
						);
					}
					unset($onePhoto);
				}
				else
				{
					if (
						isset($item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']) &&
						!empty($item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE'])
					)
					{
						$fileValues = (
							isset($item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']['ID']) ?
							array(0 => $item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']) :
							$item['DISPLAY_PROPERTIES'][$propertyCode]['FILE_VALUE']
						);
						foreach ($fileValues as &$oneFileValue)
						{
                            $file = CFile::ResizeImageGet(
                                $oneFileValue['ID'],
                                array('width' => 400, 'height' => 410),
                                BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                                false
                            );
                            $file_small = CFile::ResizeImageGet(
                                $oneFileValue['ID'],
                                array('width' => 90, 'height' => 90),
                                BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                                false
                            );
                            $result[] = array(
                                'ID' => intval($oneFileValue['ID']),
                                'SRC' => $file['src'],
                                'SRC_BIG' => $oneFileValue['SRC'],
                                'SRC_SMALL' => $file_small['src'],
                                'WIDTH' => intval($file['width']),
                                'HEIGHT' => intval($file['height'])
                            );
						}
						if (isset($oneFileValue))
							unset($oneFileValue);
					}
					else
					{
						$propValues = $item['PROPERTIES'][$propertyCode]['VALUE'];
						if (!is_array($propValues))
							$propValues = array($propValues);

						foreach ($propValues as &$oneValue)
						{
							$oneFileValue = CFile::GetFileArray($oneValue);
							if (isset($oneFileValue['ID']))
							{
                                $file = CFile::ResizeImageGet(
                                    $oneFileValue['ID'],
                                    array('width' => 400, 'height' => 410),
                                    BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                                    false
                                );
                                $file_small = CFile::ResizeImageGet(
                                    $oneFileValue['ID'],
                                    array('width' => 90, 'height' => 90),
                                    BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                                    false
                                );
                                $result[] = array(
                                    'ID' => intval($oneFileValue['ID']),
                                    'SRC' => $file['src'],
                                    'SRC_BIG' => $oneFileValue['SRC'],
                                    'SRC_SMALL' => $file_small['src'],
                                    'WIDTH' => intval($file['width']),
                                    'HEIGHT' => intval($file['height'])
                                );
							}
						}
						if (isset($oneValue))
							unset($oneValue);
					}
				}
			}
			if ($addDetailToSlider || empty($result))
			{
				if (!empty($item['DETAIL_PICTURE']))
				{
					if (!is_array($item['DETAIL_PICTURE']))
						$item['DETAIL_PICTURE'] = CFile::GetFileArray($item['DETAIL_PICTURE']);
					if (isset($item['DETAIL_PICTURE']['ID']))
					{
                        $file = CFile::ResizeImageGet(
                            $item['DETAIL_PICTURE']['ID'],
                            array('width' => 400, 'height' => 410),
                            BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                            false
                        );
                        $file_small = CFile::ResizeImageGet(
                            $item['DETAIL_PICTURE']['ID'],
                            array('width' => 90, 'height' => 90),
                            BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
                            false
                        );
						array_unshift(
							$result,
							array(
								'ID' => intval($item['DETAIL_PICTURE']['ID']),
                                'SRC' => $file['src'],
                                'SRC_BIG' => $item['DETAIL_PICTURE']['SRC'],
                                'SRC_SMALL' => $file_small['src'],
                                'WIDTH' => intval($file['width']),
                                'HEIGHT' => intval($file['height'])
							)
						);
					}
				}
			}
		}
		return $result;
	}
}
?>