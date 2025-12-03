<div class="modal-overlay" id="menu-modal">
    <div class="modal-content">

        <div class="modal-header">
            <h3 id="modal-title">Add Menu Item</h3>
            <button type="button" class="close-modal" onclick="closeMenuModal()">&times;</button>
        </div>

        <form id="menu-form">
            <input type="hidden" id="menu-id">

            <div class="modal-body">

                {{-- BASIC INFORMATION --}}
                <div class="form-section">
                    <h4 class="form-section-title">Basic Information</h4>

                    <div class="form-grid">

                        <div class="form-group">
                            <label for="menu-name" class="label-required">Item Name</label>
                            <input type="text" id="menu-name" placeholder="Enter item name" required>
                        </div>

                        <div class="form-group">
                            <label for="menu-category" class="label-required">Category</label>
                            <select id="menu-category" required>
                                <option value="">Select Category</option>
                                <option value="Western">Western</option>
                                <option value="Chinese">Chinese</option>
                                <option value="Japanese">Japanese</option>
                                <option value="Filipino">Filipino</option>
                                <option value="Desserts">Desserts</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="menu-price" class="label-required">Price (₱)</label>
                            <input type="number" id="menu-price" placeholder="0.00" min="0" step="0.01" required>
                        </div>

                    </div>

                    <div class="form-group full-width">
                        <label for="menu-description">Description</label>
                        <textarea id="menu-description" placeholder="Describe the menu item..."></textarea>
                    </div>
                </div>

                {{-- IMAGE UPLOAD --}}
                <div class="form-section">
                    <h4 class="form-section-title">Item Image</h4>

                    <div class="image-upload-container" id="image-upload-area" onclick="triggerImageUpload()">
                        <div class="image-upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>

                        <div class="image-upload-text">
                            <h4>Upload Item Image</h4>
                            <p>Drag & drop your image here or click to browse</p>
                        </div>

                        <input type="file" id="menu-image-input" class="image-upload-input" accept="image/*" onchange="handleImageSelect(event)">
                    </div>

                    <div class="image-preview-container" id="image-preview-container">
                        <div class="image-preview">
                            <img id="preview-image" src="" alt="">
                            <div class="image-preview-actions">
                                <button type="button" onclick="rotateImage()"><i class="fas fa-redo"></i></button>
                                <button type="button" onclick="removeImage()"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="image-info" id="image-info"></div>
                    </div>

                    <div class="image-upload-loading" id="image-upload-loading">
                        <div class="loading-spinner"></div>
                        <p>Processing image...</p>
                    </div>
                </div>

                {{-- SETTINGS --}}
                <div class="form-section">
                    <h4 class="form-section-title">Additional Settings</h4>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="menu-prep-time">Preparation Time (minutes)</label>
                            <input type="number" id="menu-prep-time" placeholder="e.g., 15" min="1">
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeMenuModal()">Cancel</button>

                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Save Menu Item
                </button>
            </div>

        </form>

    </div>
</div>
