<div class="w-full bg-none self-center flex cursor-pointer">
    <div
        class="w-full flex h-[46px] m-0 bg-white justify-center items-center pl-6 rounded-[35px] border-0.5 border-white">
        <label for="testId-SearchBar-Input" class="absolute w-[1px] h-[1px] p-0 overflow-hidden">
            Search Bar</label>
        <input type="text" autocomplete="off" @input="console.log($event.target.value)"
            class="bg-transparent border-0 border-none w-full text-lg h-full leading-5 text-neutral-700 tracking-wide ring-0 focus:border-0 focus:ring-0 outline-none outline-0 focus:outline-none focus:border-none focus:shadow-none shadow-none"
            id="testId-SearchBar-Input" tabindex="-1" value="" placeholder="Buscar en NEXT">
    </div>
    <button
        class="bg-next-700 rounded-[35px] focus:ring focus:ring-next-500 absolute right-0 box-border border border-white z-10 h-[46px] w-[46px] flex justify-center items-center">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="block w-full h-full p-2 text-white">
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.3-4.3" />
        </svg>
    </button>
</div>
