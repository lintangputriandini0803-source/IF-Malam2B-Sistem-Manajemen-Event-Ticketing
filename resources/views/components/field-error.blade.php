{{--
    COMPONENT: field-error
    Pesan error kecil langsung di bawah field input.
    Cocok untuk validasi per-field dari Laravel $errors.

    CARA PAKAI:
      <input type="text" name="email" ...>
      <x-field-error field="email" />

    PROPS:
      field : nama field (string), wajib
--}}

@props(['field'])

@error($field)
<p style="font-size:12px;color:#dc2626;margin-top:4px;display:flex;align-items:center;gap:4px">
    <svg style="width:12px;height:12px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
    </svg>
    {{ $message }}
</p>
@enderror
