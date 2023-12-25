@extends('website::shared.layout')

@section('menu')
    @include('website::shared.navbar')
@stop

@section('content')
    <div class="container-fluid h-auto mt-8">
        <div class="national-section w-[60%] m-auto  h-full  flex justify-between">
            <div class="left-content w-[63%] drop-shadow-sm bg-white  px-4 py-3">
                <div class="description-box w-full  h-auto  ">
                    <h3 class="text-xl font-poppinsAndHanuman text-blue-900">Workshop on the direction of strengthening the
                        mechanism and
                        policy of information
                        policy 2023</h3>
                    <div class="date flex items-center gap-1 text-base py-4 text-black opacity-90 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M5.615 21q-.69 0-1.152-.462Q4 20.075 4 19.385V6.615q0-.69.463-1.152Q4.925 5 5.615 5h1.77V3.308q0-.233.153-.386q.152-.153.385-.153t.386.153q.153.153.153.386V5h7.153V3.27q0-.214.144-.357t.356-.144q.214 0 .357.143t.143.357V5h1.77q.69 0 1.152.463q.463.462.463 1.152v12.77q0 .69-.462 1.152q-.463.463-1.153.463zm0-1h12.77q.23 0 .423-.192q.192-.193.192-.423v-8.77H5v8.77q0 .23.192.423q.193.192.423.192M5 9.615h14v-3q0-.23-.192-.423Q18.615 6 18.385 6H5.615q-.23 0-.423.192Q5 6.385 5 6.615zm0 0V6zm7 4.539q-.31 0-.54-.23q-.23-.23-.23-.54q0-.309.23-.539q.23-.23.54-.23q.31 0 .54.23q.23.23.23.54q0 .31-.23.539q-.23.23-.54.23m-4 0q-.31 0-.54-.23q-.23-.23-.23-.54q0-.309.23-.539q.23-.23.54-.23q.31 0 .54.23q.23.23.23.54q0 .31-.23.539q-.23.23-.54.23m8 0q-.31 0-.54-.23q-.23-.23-.23-.54q0-.309.23-.539q.23-.23.54-.23q.31 0 .54.23q.23.23.23.54q0 .31-.23.539q-.23.23-.54.23M12 18q-.31 0-.54-.23q-.23-.23-.23-.54q0-.309.23-.539q.23-.23.54-.23q.31 0 .54.23q.23.23.23.54q0 .31-.23.54Q12.31 18 12 18m-4 0q-.31 0-.54-.23q-.23-.23-.23-.54q0-.309.23-.539q.23-.23.54-.23q.31 0 .54.23q.23.23.23.54q0 .31-.23.54Q8.31 18 8 18m8 0q-.31 0-.54-.23q-.23-.23-.23-.54q0-.309.23-.539q.23-.23.54-.23q.31 0 .54.23q.23.23.23.54q0 .31-.23.54Q16.31 18 16 18" />
                        </svg>
                        <span class="">20/October/2022</span>
                    </div>
                    <p class="text-lg font-poppinsAndHanuman">This "direction of strengthening the mechanism and policy of
                        information policy" aims
                        to promote and
                        build good governance in the field of information. To inspire and promote the positive success of
                        the past 5 years of the Capital-Provincial Administration and the work Forms respond to the current
                        situation in providing better public services and modernizing national and sub-national policies. As
                        well as advocacy at the sub-national level.</p>
                </div>
                <div class="video-box rounded-md overflow-hidden w-full h-[390px] mt-2 bg-red-300">
                    <iframe width="100%" height="100%"
                        src="https://www.youtube.com/embed/Q9xX4KDVcwc?si=aCiWjMr15SLMs3Jy" title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen></iframe>
                </div>
                <div class="image-box w-full h-auto  mt-4">
                    <div class="img w-full h-full">
                        <img class="w-full h-full object-contain" src="./images/cards/card_img1.jpg" alt="">
                    </div>
                    <div class="img w-full h-full">
                        <img class="w-full h-full object-contain" src="./images/cards/card_img2.jpg" alt="">
                    </div>
                    <div class="img w-full h-full">
                        <img class="w-full h-full object-contain" src="./images/cards/card_img3.jpg" alt="">
                    </div>
                    <div class="img w-full h-full">
                        <img class="w-full h-full object-contain" src="./images/cards/card_img4.jpg" alt="">
                    </div>
                </div>
            </div>
            <div class="asside-content w-[35%] h-[400px] ">

            </div>
        </div>
    </div>
@stop

@section('footer')
    @include('website::shared.footer')
@stop
