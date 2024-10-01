@extends('layouts.app')

@section('title', 'Cobertura')

@section('contenidoHome')
<div class="relative w-full min-h-screen bg-fixed bg-cover bg-center" style="background-image: url('medico.jpeg');">

    <div class="absolute inset-0 bg-black opacity-50"></div>

    <div class="relative z-10 w-full px-4 py-8">
        <div class="mb-4 mt-32">
            <div class="relative flex" data-twe-input-wrapper-init data-twe-input-group-ref>
                <input type="text"
                    class="peer block min-h-[auto] w-full rounded border-2 border-white bg-transparent px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-white text-white placeholder:hidden"
                    aria-label="Search" id="search-input"
                    aria-describedby="search-button" />
                <label for="search-input"
                    class="pointer-events-none absolute left-3 top-0 mb-0 max-w-[90%] origin-[0_0] truncate pt-[0.37rem] leading-[1.6] text-white transition-all duration-200 ease-out peer-focus:-translate-y-[0.9rem] peer-focus:scale-[0.8] peer-focus:text-white">
                    Buscar obra social
                </label>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6" id="obraList">
            @foreach ($obras_sociales as $obra)
            <div class="obra-item bg-neutral-50 py-4 px-6 rounded-lg shadow-md flex items-center justify-center"
                style="background-color: rgba(245, 245, 245, 0.8);">
                <span class="text-center"><strong>{{ $obra->nombre }}</strong></span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function filterObras() {
    const input = document.getElementById('search-input').value.toLowerCase();
    const obraItems = document.querySelectorAll('.obra-item');

    obraItems.forEach(item => {
        const obraText = item.textContent.toLowerCase();
        item.style.display = obraText.includes(input) ? '' : 'none';
    });
}

document.getElementById('search-input').addEventListener('keyup', filterObras);
</script>

<style>
label {
    pointer-events: none; 
    z-index: 1; 
}
</style>
@endsection
