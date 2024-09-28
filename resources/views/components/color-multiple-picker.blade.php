<style>
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown input[type="text"] {
        width: 200px;
        padding: 10px;
        border: 1px solid #fff;
        border-radius: 5px;
        font-size: 16px;
    }

    .dropdown-menu {
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        max-height: 150px;
        overflow-y: auto;
        width: 100%;
        z-index: 1;
    }

    .dropdown-menu-item {
        padding: 10px;
        cursor: pointer;
    }

    .selected-items {
        margin-top: 10px;
    }

    .selected-item {
        display: inline-block;
        margin-right: 5px;
        margin-bottom: 5px;
        padding: 5px 10px;
        border-radius: 5px;
        background-color: #e0e0e0;
    }

    .close-button {
        cursor: pointer;
        margin-left: 5px;
    }
</style>
<div class="dropdown">
    <input type="text" placeholder="Search colors..." id="colorInput">
    <div class="dropdown-menu" id="colorDropdown">
    </div>
</div>



