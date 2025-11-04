@props(['message' => 'Are you sure you want to delete this item?'])

<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="warning-icon">
           <span><i class="fs-1 fa-solid fa-triangle-exclamation"></i></span>
        </div>
        <h3>Confirm Deletion</h3>
        <p>{{$message}}</p>
        <div class="modal-buttons">
            <button onclick="confirmDelete()" class="delbtn btn-danger">Delete</button>
            <button onclick="closeDeleteModal()" class="delbtn btn-cancel">Cancel</button>
        </div>
    </div>
</div>

<style>
/* Modal background */
.modal {
    display: none;
    justify-content: center;
    align-items: center;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

/* Show modal */
.modal.show {
    display: flex;
}

/* Modal box */
.modal-content {
    background: white;
    padding: 30px 20px;
    border-radius: 12px;
    text-align: center;
    max-width: 400px;
    width: 90%;
    box-shadow: 0px 5px 15px rgba(0,0,0,0.2);
    animation: popIn 0.3s ease;
    position: relative;
    p{
        color:#666;
    }
}

/* Warning icon */
.warning-icon {
    margin-bottom: 10px;
    /* animation: pulse 1.2s infinite; */
}

.warning-icon span i {
    color: #28364f;
}

/* Button container */
.modal-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 25px;
    gap: 15px;
}

/* Common button styles */
.delbtn {
    padding: 12px 20px;
    font-size: 16px;
    font-weight: 400;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    flex: 1;
    transition: background-color 0.3s, transform 0.2s;
}

/* Delete button */
.btn-danger {
    background-color:#28364f;
    color: white;
}

.btn-danger:hover {
    background-color: #30415d;
}

/* Cancel button */
.btn-cancel {
    background-color: #e1e0e0;
    color: #333;
}

.btn-cancel:hover {
    background-color: #d6d6d6;

}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes popIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Warning icon pulse animation */
@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
    100% { transform: scale(1); opacity: 1; }
}
</style>