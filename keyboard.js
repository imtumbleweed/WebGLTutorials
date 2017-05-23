// ASCII codes
var KEY_LEFT = 37;
var KEY_RIGHT = 39;
var KEY_UP = 38;
var KEY_DOWN = 40;
var KEY_W = 87;
var KEY_S = 83;
var KEY_A = 65;
var KEY_D = 68;

var DIR_E = 1;
var DIR_NE = 2;
var DIR_N = 4;
var DIR_NW = 8;
var DIR_W = 16;
var DIR_SW = 32;
var DIR_S = 64;
var DIR_SE = 128;

var isShift = false; // var key = [false, false, false, false];
window.key = null;

var Keyboard = function() {
    this.left = false;
    this.right = false;
    this.up = false;
    this.down = false;
    this.w = false;
    this.s = false;
    this.a = false;
    this.d = false;
};

function InitializeKeyboard()
{
    window.key = new Keyboard();

    $(document).keydown(function(e) {
        if (e.keyCode == 16) isShift = true;
        if (e.keyCode == KEY_LEFT) { key.left = true; }
        if (e.keyCode == KEY_RIGHT) { key.right = true; }
        if (e.keyCode == KEY_UP) { key.up = true; }
        if (e.keyCode == KEY_DOWN) { key.down = true; }
        if (e.keyCode == KEY_W) { key.w = true; }
        if (e.keyCode == KEY_S) { key.s = true; }
        if (e.keyCode == KEY_A) { key.a = true; }
        if (e.keyCode == KEY_D) { key.d = true; }
        console.log(e.keyCode);
    });

    $(document).keyup(function(e) {
        if (e.keyCode == 16) isShift = false;
        if (e.keyCode == KEY_LEFT) { key.left = false; }
        if (e.keyCode == KEY_RIGHT) { key.right = false; }
        if (e.keyCode == KEY_UP) { key.up = false; }
        if (e.keyCode == KEY_DOWN) { key.down = false; }
        if (e.keyCode == KEY_W) { key.w = false; }
        if (e.keyCode == KEY_S) { key.s = false; }
        if (e.keyCode == KEY_A) { key.a = false; }
        if (e.keyCode == KEY_D) { key.d = false; }
    });
}
