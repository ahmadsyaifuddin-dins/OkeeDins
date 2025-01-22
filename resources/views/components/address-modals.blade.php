<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Alamat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addAddressForm">
                    <div class="mb-3">
                        <label class="form-label">Label Alamat</label>
                        <input type="text" class="form-control" name="label" placeholder="Rumah, Kantor, dll">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Penerima</label>
                        <input type="text" class="form-control" name="receiver_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="tel" class="form-control" name="phone_number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" name="full_address" rows="3" required></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input checkbox-custom" name="is_primary" id="makeAddressPrimary" value="1">
                        <label class="form-check-label" for="makeAddressPrimary">Jadikan Alamat Utama</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-custom" onclick="saveNewAddress()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Alamat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editAddressForm">
                    <input type="hidden" name="address_id">
                    <div class="mb-3">
                        <label class="form-label">Label Alamat</label>
                        <input type="text" class="form-control" name="label" placeholder="Rumah, Kantor, dll">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Penerima</label>
                        <input type="text" class="form-control" name="receiver_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="tel" class="form-control" name="phone_number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" name="full_address" rows="3" required></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input checkbox-custom" name="is_primary" id="editMakeAddressPrimary" value="1">
                        <label class="form-check-label" for="editMakeAddressPrimary">Jadikan Alamat Utama</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-custom" onclick="updateAddress()">Simpan</button>
            </div>
        </div>
    </div>
</div>
