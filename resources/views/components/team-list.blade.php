<div class="flex p-6 border-2 border-dashed border-blue-500 mt-10 rounded-md mx-3" id="teamList">
    @if (!empty($team))
        Equipo
    @else
        <span class="text-center w-full text-xl">No has agregado ningún pokemón a tu equipo</span>
    @endif
</div>
