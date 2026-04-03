@extends('admin.layout')

@section('title', 'Site Settings & GST Control')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold"><i class="fas fa-cog me-2"></i> GST Control & General Settings</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- GST Configuration Section -->
                        <div class="col-12 mb-2">
                            <h6 class="fw-bold border-bottom pb-2" style="color: #0f7e5dff;">
                                <i class="fas fa-file-invoice-dollar me-2"></i> GST Calculation Settings
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="gst_status" class="form-label fw-semibold">GST Status</label>
                                <select name="gst_status" id="gst_status" class="form-select @error('gst_status') is-invalid @enderror">
                                    <option value="1" {{ $settings->gst_status ? 'selected' : '' }}>Active (Include Tax in Checkout)</option>
                                    <option value="0" {{ !$settings->gst_status ? 'selected' : '' }}>Inactive (No Tax)</option>
                                </select>
                                <div class="form-text small">When active, GST percentage will be applied to the subtotal in the checkout page.</div>
                                @error('gst_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="gst_percentage" class="form-label fw-semibold">GST Percentage (%)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" name="gst_percentage" id="gst_percentage" class="form-control @error('gst_percentage') is-invalid @enderror" value="{{ old('gst_percentage', $settings->gst_percentage) }}" placeholder="e.g. 18.00">
                                    <span class="input-group-text">%</span>
                                </div>
                                 <div class="form-text small">Minimum amount for GST calculation 0</div>
                                @error('gst_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Footer Settings -->
                        <div class="col-12 mt-4 mb-2">
                            <h6 class="fw-bold border-bottom pb-2" style="color: #0f7e5dff;">
                                <i class="fas fa-shoe-prints me-2"></i>Header & Footer
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="header_title" class="form-label fw-semibold">Header Title</label>
                                <input type="text" name="header_title" id="header_title" class="form-control" value="{{ old('header_title', $settings->header_title) }}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="footer_content" class="form-label fw-semibold">Footer About Content</label>
                                <textarea name="footer_content" id="footer_content" class="form-control" rows="3">{{ old('footer_content', $settings->footer_content) }}</textarea>
                            </div>
                        </div>


                        <!-- General Website Settings -->
                        <div class="col-12 mt-4 mb-2">
                            <h6 class="fw-bold border-bottom pb-2" style="color: #0f7e5dff;">
                                <i class="fas fa-globe me-2"></i> Contact Information
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label fw-semibold">Contact Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $settings->email) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="mobile_no" class="form-label fw-semibold">Mobile Number</label>
                                <input type="text" name="mobile_no" id="mobile_no" class="form-control" value="{{ old('mobile_no', $settings->mobile_no) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="address" class="form-label fw-semibold">Office Address</label>
                                <textarea name="address" id="address" class="form-control" rows="3">{{ old('address', $settings->address) }}</textarea>
                            </div>
                        </div>

                        <!-- WhatsApp Settings -->
                        <div class="col-12 mt-4 mb-2">
                            <h6 class="fw-bold border-bottom pb-2" style="color: #0f7e5dff;">
                                <i class="fab fa-whatsapp me-2"></i> WhatsApp Settings
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="whatsapp_no" class="form-label fw-semibold">WhatsApp Number (with country code e.g. 919876543212)</label>
                                <input type="text" name="whatsapp_no" id="whatsapp_no" class="form-control" value="{{ old('whatsapp_no', $settings->whatsapp_no) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="whatsapp_msg_1" class="form-label fw-semibold">WhatsApp Message 1</label>
                                <input type="text" name="whatsapp_msg_1" id="whatsapp_msg_1" class="form-control" value="{{ old('whatsapp_msg_1', $settings->whatsapp_msg_1) }}" placeholder="e.g. Chat with us">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="whatsapp_msg_2" class="form-label fw-semibold">WhatsApp Message 2</label>
                                <input type="text" name="whatsapp_msg_2" id="whatsapp_msg_2" class="form-control" value="{{ old('whatsapp_msg_2', $settings->whatsapp_msg_2) }}" placeholder="e.g. Enquire and Order">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="whatsapp_msg_3" class="form-label fw-semibold">WhatsApp Message 3</label>
                                <input type="text" name="whatsapp_msg_3" id="whatsapp_msg_3" class="form-control" value="{{ old('whatsapp_msg_3', $settings->whatsapp_msg_3) }}" placeholder="e.g. Green World">
                            </div>
                        </div>

                        <!-- SEO Settings
                        <div class="col-12 mt-4 mb-2">
                            <h6 class="fw-bold border-bottom pb-2">
                                <i class="fas fa-search me-2"></i> SEO Meta Information (Home Page)
                            </h6>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="home_meta_title" class="form-label fw-semibold">Home Meta Title</label>
                                <input type="text" name="home_meta_title" id="home_meta_title" class="form-control" value="{{ old('home_meta_title', $settings->home_meta_title) }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="home_meta_keywords" class="form-label fw-semibold">Home Meta Keywords</label>
                                <textarea name="home_meta_keywords" id="home_meta_keywords" class="form-control" rows="2">{{ old('home_meta_keywords', $settings->home_meta_keywords) }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="home_meta_description" class="form-label fw-semibold">Home Meta Description</label>
                                <textarea name="home_meta_description" id="home_meta_description" class="form-control" rows="2">{{ old('home_meta_description', $settings->home_meta_description) }}</textarea>
                            </div>
                        </div> -->

                        
                        <!-- Social Media Links -->
                        <div class="col-12 mt-4 mb-2">
                            <h6 class="fw-bold border-bottom pb-2" style="color: #0f7e5dff;">
                                <i class="fas fa-share-alt me-2"></i> Social Media Links
                            </h6>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="facebook_link" class="form-label fw-semibold">Facebook URL</label>
                                <input type="url" name="facebook_link" id="facebook_link" class="form-control" value="{{ old('facebook_link', $settings->facebook_link) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="twitter_link" class="form-label fw-semibold">Twitter URL</label>
                                <input type="url" name="twitter_link" id="twitter_link" class="form-control" value="{{ old('twitter_link', $settings->twitter_link) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="insta_link" class="form-label fw-semibold">Instagram URL</label>
                                <input type="url" name="insta_link" id="insta_link" class="form-control" value="{{ old('insta_link', $settings->insta_link) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="linkedin_link" class="form-label fw-semibold">LinkedIn URL</label>
                                <input type="url" name="linkedin_link" id="linkedin_link" class="form-control" value="{{ old('linkedin_link', $settings->linkedin_link) }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="youtube_link" class="form-label fw-semibold">YouTube URL</label>
                                <input type="url" name="youtube_link" id="youtube_link" class="form-control" value="{{ old('youtube_link', $settings->youtube_link) }}">
                            </div>
                        </div>

                        

                        <div class="col-md-12 mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i> Save Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

