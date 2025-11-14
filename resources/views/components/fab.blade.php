<style>
    .fab-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
    }

    .fab-btn {
        background-color: #007bff;
        color: #fff;
        padding: 12px 20px;
        font-size: 16px;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s ease;
    }

    .fab-btn:hover {
        background-color: #0056b3;
    }

    .fab-options {
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transition: max-height 0.4s ease, opacity 0.4s ease;
        margin-top: 10px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .fab-options.show {
        max-height: 500px;
        opacity: 1;
    }

    .fab-option {
        background-color: white;
        padding: 10px 15px;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        font-size: 15px;
        white-space: nowrap;
    }

    .fab-option a {
        text-decoration: none;
        color: #333;
    }

    .fab-option a:hover {
        color: #007bff;
    }
</style>

<div class="fab-container">
    <button class="fab-btn" onclick="toggleFabOptions()">Direct Me</button>
    <div class="fab-options" id="fabOptions">
        <div class="fab-option"><a href="{{ route('contactus.getusintouch') }}">Contact Us</a></div>
    </div>
</div>

<script>
    function toggleFabOptions() {
        const options = document.getElementById('fabOptions');
        options.classList.toggle('show');
    }
</script>
