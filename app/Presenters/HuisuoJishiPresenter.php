<?php

namespace App\Presenters;

use App\Models\HuisuoJishi;

class HuisuoJishiPresenter
{
    protected $attachmentPresenter;
    
    public function __construct(AttachmentPresenter $attachmentPresenter)
    {
        $this->attachmentPresenter = $attachmentPresenter;   
    }
    
    public function getAddress(HuisuoJishi $huisuoJishi)
    {
        return sprintf("%s-%s-%s, %s", $huisuoJishi->province, $huisuoJishi->city, $huisuoJishi->district, $huisuoJishi->address);
    }
    
    public function listContacts(HuisuoJishi $huisuoJishi)
    {
        $contactHtmlArr = [];
        foreach ($huisuoJishi->contacts as $contact)
        {
            $one = $contact->type . ': ' . $contact->account;
            if ($contact->image)
            {
                $image = $this->attachmentPresenter->getAttachmentImageLink($contact->image);
                $one .= sprintf(
                    '<a href="%s" target="_blank"><img src="%s" style="width: 32px;height: 32px;margin-left: 10px"/></a>',
                    $image, $image
                );
            }
            $contactHtmlArr[] = $one;
        }
        return implode('<br/>', $contactHtmlArr);
    }
}