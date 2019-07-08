<?php

class Super_Cat_Tab {

    public function __construct(){
        add_action( 'admin_init', array( $this, 'setup_init' ) );
    }

    public function setup_init() {
    }

    public function content(){
        ?>


        <style type="text/css">#bg-canvas {
            background-color: #333;
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left:0;
            z-index: -1;
            font-family: Verdana;
        }
        .CatEyes{
            margin-top: 0;
            margin-bottom: 0;

        }
        .CatEyes {
            position: relative;
            height: 300px;
            width: 300px;
            background-color: #333;
            /* background-image: url(../imgs/e.png); */
            background-position: center;
            border-radius: 100%;
            display: block;
        }

        #cat-container{
            height: 100%;
            width: 100%;
            -webkit-transition: all 300ms linear;
            -o-transition: all 300ms linear;
            transition: all 300ms linear;
            -webkit-transform-origin: center;
            -ms-transform-origin: center;
            transform-origin: center;
            position: absolute;
            top: 0px;
            left: 0px;

        }

        #left-eye, #right-eye {
            height: 50px;
            width: 50px;
            position: absolute;
            top: 151px;
            left: 98px;
            width: 35px;
            height: 34px;

        }

        #right-eye {
            left: auto;
            right: 98px;

        }

        #left-eye::before, #right-eye::before { /* La caccola */
            content: "";
            position: absolute;
            width: 0px;
            height: 0px;
            border-left: 5px solid #fff;
            border-top: 3px solid transparent;
            border-bottom: 3px solid transparent;
            top: 18px;
            right: -2px;

        }

        #right-eye::before { /* La caccola */
            border-right: 5px solid #fff;
            border-left: none;
            left: -2px;

        }

        #left-eye1, #right-eye1 {
            display:block;
            width: 35px;
            height: 21px;
            position: absolute;
            top: 0px;
            left: 0px;
            overflow: hidden;

        }

        #left-eye1::before, #right-eye1::before {
            content: "";
            background-color: #fff;
            display:block;
            width: 35px;
            height: 42px;
            position: absolute;
            top: 0px;
            left: 0px;
            /*border-radius: 50%  50%  50%  50% / 70%  70%  50%  50%;*/
            border-radius: 50%;

        }

        #left-eye2, #right-eye2 {
            background-color: transparent;
            display:block;
            width: 35px;
            height: 21px;
            position: absolute;
            top: 21px;
            left: 0px;
            overflow: hidden;

        }

        #left-eye2::before, #right-eye2::before {
            content: "";
            background-color: #fff;
            display:block;
            width: 37px;
            height: 35px;
            position: absolute;
            top: -22px;
            left: -1px;
            /*border-radius: 50%  50%  50%  50% / 70%  70%  50%  50%;*/
            border-radius: 100%;

        }

        #left-pupil, #right-pupil {
            position: absolute;
            top: 0px;
            left: 0px;
            margin-top: 10px;
            margin-left: 15px;
            background-color: #333;
            width: 6px;
            height: 14px;
            border-radius: 100%;
            -webkit-transition: all 80ms;
            -o-transition: all 80ms;
            transition: all 80ms;

        }

        #left-pupil::after, #right-pupil::after {
            content: "";
            position: absolute;
            top: 4px;
            right: -1px;
            background-color: #fff;
            width: 3px;
            height: 3px;
            border-radius: 100%;

        }

        #left-ear, #right-ear{
            position: absolute;
            top: 95px;
            width: 0px;
            height: 0px;
            opacity: 1;
            z-index: 3;
            -webkit-transform-origin: center bottom;
            -ms-transform-origin: center bottom;
            transform-origin: center bottom;

        }
        #left-ear{
            left: 90px;
            border-bottom: 49px solid #fff;
            border-left: 40px solid #333;
            border-top: 0px solid blue;
            border-bottom-right-radius: 40% 10%;
            border-bottom-left-radius: 0% 20%;
            border-top-left-radius: 10% 0%;
            -webkit-transform: rotate(-30deg);
            -ms-transform: rotate(-30deg);
            transform: rotate(-30deg);

        }
        #right-ear{
            right: 90px;
            border-bottom: 49px solid #fff;
            border-right: 40px solid #333;
            border-top: 0px solid blue;
            border-bottom-left-radius: 40% 10%;
            border-bottom-right-radius: 0% 20%;
            border-top-right-radius: 10% 0%;
            -webkit-transform: rotate(30deg);
            -ms-transform: rotate(30deg);
            transform: rotate(30deg);

        }

        #left-ear::before {
            content: "";
            position: absolute;
            top: -7px;
            left: -25px;
            width: 10px;
            height: 64px;
            background-color: #fff;
            border-radius: 100%;
            -webkit-transform: rotate(38deg);
            -ms-transform: rotate(38deg);
            transform: rotate(38deg);
            -webkit-box-shadow: -16px 0px 0px 15px #333;
            box-shadow: -16px 0px 0px 15px #333;

        }

        #right-ear::before {
            content: "";
            position: absolute;
            top: -7px;
            right: -25px;
            width: 10px;
            height: 64px;
            background-color: #fff;
            border-radius: 100%;
            -webkit-transform: rotate(-38deg);
            -ms-transform: rotate(-38deg);
            transform: rotate(-38deg);
            -webkit-box-shadow: 16px 0px 0px 15px #333;
            box-shadow: 16px 0px 0px 15px #333;

        }

        #left-ear::after {
            content: "";
            position: absolute;
            top: 41px;
            right: 1px;
            width: 2px;
            height: 6px;
            background-color: #333;
            border-radius: 100%;
            -webkit-transform: rotate(-15deg);
            -ms-transform: rotate(-15deg);
            transform: rotate(-15deg);
            -webkit-box-shadow: -3px 3px 0px 0px #333,
            -6px 4px 0px 0px #333;
            box-shadow: -3px 3px 0px 0px #333,
            -6px 4px 0px 0px #333;

        }

        #right-ear::after {
            content: "";
            position: absolute;
            top: 41px;
            left: 1px;
            width: 2px;
            height: 6px;
            background-color: #333;
            border-radius: 100%;
            -webkit-transform: rotate(15deg);
            -ms-transform: rotate(15deg);
            transform: rotate(15deg);
            -webkit-box-shadow: 3px 3px 0px 0px #333,
            6px 4px 0px 0px #333;
            box-shadow: 3px 3px 0px 0px #333,
            6px 4px 0px 0px #333;

        }

        #nose {
            position: absolute;
            width: 10px;
            height: 3px;
            background-color: transparent;
            top:180px;
            left: 145px;
            border-top:3px solid #fff;
            border-top-left-radius:100%;
            border-top-right-radius:100%;

        }
        #nose::after {
            content: "";
            position: absolute;
            width: 0px;
            height: 0px;
            top:0px;
            left: 0px;
            border-top:3px solid #fff;
            border-left:5px solid transparent;
            border-right:5px solid transparent;

        }

        #left-eyelid, #right-eyelid {
            width: 50px;
            height: 50px;
            position: absolute;
            background-color: #333;
            border-radius: 100%;
            top: -17px;
            left: -8px;
            margin-top: -35px;
            -webkit-transition: margin 150ms;
            -o-transition: margin 150ms;
            transition: margin 150ms;

        }

        #right-eyelid {
            left: auto;
            right: -8px;

        }
    </style>


    <center>

        <div class="CatEyes">

            <div id="cat-container" style="top: 0; left: 0;">

                <div id="left-ear" style="transform: rotate(-32deg);">
                </div>
                <div id="right-ear" style="transform: rotate(32deg);">
                </div>
                <div id="nose">
                </div>
                <div id="left-eye">
                    <div id="left-eye1">
                    </div>
                    <div id="left-eye2">
                    </div>
                    <div id="left-pupil" style="top: 0; left: 0;">
                    </div>
                    <div id="left-eyelid" style="margin-top: -35px;">
                    </div>
                </div>
                <div id="right-eye">
                    <div id="right-eye1">
                    </div>
                    <div id="right-eye2">
                    </div>
                    <div id="right-pupil" style="top: 0; left: 0;">
                    </div>
                    <div id="right-eyelid" style="margin-top: -35px;">
                    </div>
                </div>
            </div>
        </div>
        <p>
            Super Powers for Elementor.<br>
            For Marketers by Marketers.<br>
            Made with ❤ by <a href="https://mirai-bay.com" target="_blank">Mirai Bay</a>
        </p>
    </center>





    <?php
}



}
