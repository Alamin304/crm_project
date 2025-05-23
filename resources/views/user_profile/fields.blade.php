<style>
    .form-group {
        margin-bottom: 5px !important;
    }
</style>
<div class="card author-box card-primary  " style="margin-bottom: 10px !important;">

    <div class="row  pt-1">

        <div class="col-sm-12 ">
            <div class="author-box-left">
                <img alt="image" src="{{ getLoggedInUser()->image_url ?? '' }}"
                    class="rounded-circle user-profile-image " alt="InfyOm">
                <div class="clearfix"></div>
            </div>
            <div class="author-box-details">
                <div class="author-box-name mt-2">
                    <span class="font-weight-bold">{{ html_entity_decode($user->full_name) }}</span><br>
                    <p style="font-size: 14px;">
                        {{ __('messages.change_password.change_information_about_yourself_on_this_page') }}</p>

                </div>
                @if ($user->facebook != null)
                    <a href="{{ $user->facebook }}" class="btn btn-social-icon facebook-color mr-2" target="_blank">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                @endif
                @if ($user->skype != null)
                    <a href="{{ $user->skype }}" class="btn btn-social-icon skype-color mr-2" target="_blank">
                        <i class="fab fa-skype"></i>
                    </a>
                @endif
                @if ($user->linkedin != null)
                    <a href="{{ $user->linkedin }}" class="btn btn-social-icon linkedin-color mr-2" target="_blank">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                @endif
            </div>
        </div>
        {{-- <div class="col-sm-6 ">
                    <h2 class="section-title ">{{ $user->full_name }}</h2>
                    <p class="section-lead">
                        {{ __('messages.change_password.change_information_about_yourself_on_this_page') }}
                    </p>
                </div> --}}
    </div>

</div>




<div class="card ">
    <form method="post" class="needs-validation" novalidate="">

        <div class="card-body pb-0">
            <div class="row ">
                <div class="form-group col-md-6 col-12 p-1">
                    {{ Form::label('first_name', __('messages.member.first_name')) }}<span class="required">*</span>
                    {{ Form::text('first_name', null, ['class' => 'form-control', 'required', 'autocomplete' => 'off', 'placeholder' => __('messages.member.first_name')]) }}
                </div>
                <div class="form-group col-md-6 col-12 p-1">
                    {{ Form::label('last_name', __('messages.member.last_name')) }}
                    {{ Form::text('last_name', null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => __('messages.member.last_name')]) }}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 col-12 p-1">
                    {{ Form::label('email', __('messages.common.email')) }}<span class="required">*</span>
                    {{ Form::email('email', null, ['class' => 'form-control', 'required', 'autocomplete' => 'off']) }}
                </div>
                <div class="form-group col-md-6 col-12 p-1">
                    {{ Form::label('phone', __('messages.customer.phone')) }}<br>
                    {{ Form::tel('phone', null, ['class' => 'form-control', 'id' => 'phoneNumber', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"",)']) }}
                    {{ Form::hidden('prefix_code', old('prefix_code'), ['id' => 'prefix_code']) }}
                    <span id="valid-msg" class="hide">{{ __('messages.placeholder.valid_number') }}</span>
                    <span id="error-msg" class="hide"></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6 p-1">
                    {{ Form::label('facebook', __('messages.member.facebook')) }}
                    {{ Form::text('facebook', null, ['class' => 'form-control', 'id' => 'facebookUrl', 'autocomplete' => 'off']) }}
                </div>
                <div class="form-group col-sm-6 p-1">
                    {{ Form::label('linkedin', __('messages.member.linkedin')) }}
                    {{ Form::text('linkedin', null, ['class' => 'form-control', 'id' => 'linkedInUrl', 'autocomplete' => 'off']) }}
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6 p-1">
                    {{ Form::label('skype', __('messages.member.skype')) }}
                    {{ Form::text('skype', null, ['class' => 'form-control', 'id' => 'skypeUrl', 'autocomplete' => 'off']) }}
                </div>
                <div class="form-group col-sm-12 col-md-6 col-lg-3 p-1">
                    <span id="validationErrorBox" class="text-danger"></span>
                    <div class="row no-gutters">
                        <div class="col-6">
                            {{ Form::label('image', __('messages.user.profile'), ['class' => 'profile-label-color']) }}
                            <label class="image__file-upload"> {{ __('messages.setting.choose') }}
                                {{ Form::file('image', ['id' => 'profileImage', 'class' => 'd-none', 'accept' => 'image/*']) }}
                            </label>
                        </div>
                        <div class="col-2">
                            <div class="col-sm-4 preview-image-video-container pl-0 mt-1">
                                <img id='previewImage' class="img-thumbnail thumbnail-preview"
                                    src="{{ getLoggedInUser()->image_url ?? '' }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            {{ Form::submit(__('messages.common.submit'), ['id' => 'btnSave', 'class' => 'btn btn-primary']) }}

        </div>
    </form>
</div>
