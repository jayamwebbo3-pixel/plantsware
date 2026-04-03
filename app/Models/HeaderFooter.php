<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeaderFooter extends Model
{
    protected $table = 'header_footer';

    protected $fillable = [
        'header_title',
        'footer_content',
        'facebook_link',
        'twitter_link',
        'linkedin_link',
        'youtube_link',
        'insta_link',
        'footer_title',
        'footer_contact_title',
        'email',
        'address',
        'mobile_no',
        'whatsapp_no',
        'whatsapp_msg_1',
        'whatsapp_msg_2',
        'whatsapp_msg_3',
        'gst_status',
        'gst_percentage',
        'home_meta_title',
        'home_meta_keywords',
        'home_meta_description',
    ];
}

