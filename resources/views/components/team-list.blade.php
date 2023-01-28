<div class="flex flex-wrap p-6 pb-0 border-2 border-dashed border-blue-500 mt-6 rounded-md mx-3">
    @if (!empty($team))
        @foreach ($team as $pokemon)
            <div class="p-3 border-2 border-sky-700 mr-3 rounded-md pokemon flex mb-3 pr-3">
                <div class="flex flex-col border-r-2 border-sky-700 ">
                    <strong class="text-center text-xl uppercase">{{ $pokemon['name'] }}</strong>
                    <img src="{{ $pokemon['sprite'] }}" alt="Imagen del pokemón">
                </div>
                <div class="flex flex-col pl-3">
                    <span>height: {{ $pokemon['height'] * 10 }}cm</span>
                    <span>weight: {{ $pokemon['weight'] / 10 }}kg</span>
                    <strong>Stats:</strong>
                    @foreach ($pokemon['stats'] as $stat)
                        <span>{{ $stat['name'] }}: {{ $stat['pivot']['base_stat'] }}</span>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <span class="text-center w-full text-xl">No has agregado ningún pokemón a tu equipo</span>
    @endif
</div>
