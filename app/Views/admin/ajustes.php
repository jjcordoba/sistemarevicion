<div class="card radius-10 border-start border-0 border-3 border-info">
    <form action="<?php echo base_url('admin/modificar') ?>" method="POST">
        <div class="card-body">
            <?php if (!empty(session()->getFlashdata('modificado'))) { ?>
                <div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-success"><i class='bx bxs-check-circle'></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-success">Mensaje</h6>
                            <div><?php echo session()->getFlashdata('modificado'); ?></div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } ?>
            <h5 class="card-title">DATOS DE LA EMPRESA</h5>
            <hr>
            <div class="alert border-0 border-start border-5 border-info alert-dismissible fade show py-2">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-info"><i class='bx bx-info-square'></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 text-info">Advertencia</h6>
                        <div>Todo los campos con <b class="text-danger">*</b> son obligatorios</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> 
            </div>
            <?php csrf_field(); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input id="identidad" type="text" name="identidad" class="form-control" placeholder="N° Identidad" value="<?= set_value('identidad', $data['identidad']); ?>">
                        <?php if (isset($validation)) { ?>
                            <span class="text-danger font-weight-bold"><?php echo $validation->getError('identidad'); ?></span>
                        <?php } ?>
                        <label for="identidad">N° Identidad <b class="text-danger">*</b></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input id="id" type="hidden" name="id" value="<?php echo $data['id'] ?>">
                        <input id="nombre" type="text" name="nombre" class="form-control" placeholder="Nombre" value="<?= set_value('nombre', $data['nombre']) ?>">
                        <?php if (isset($validation)) { ?>
                            <span class="text-danger font-weight-bold"><?php echo $validation->getError('nombre'); ?></span>
                        <?php } ?>
                        <label for="nombre">Nombre <b class="text-danger">*</b></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input id="correo" type="email" name="correo" class="form-control" placeholder="Correo" value="<?= set_value('correo', $data['correo']) ?>">
                        <?php if (isset($validation)) { ?>
                            <span class="text-danger font-weight-bold"><?php echo $validation->getError('correo'); ?></span>
                        <?php } ?>
                        <label for="correo">Correo <b class="text-danger">*</b></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input id="telefono" type="text" name="telefono" class="form-control" placeholder="Teléfono" value="<?= set_value('telefono', $data['telefono']) ?>">
                        <?php if (isset($validation)) { ?>
                            <span class="text-danger font-weight-bold"><?php echo $validation->getError('telefono'); ?></span>
                        <?php } ?>
                        <label for="telefono">Teléfono <b class="text-danger">*</b></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input id="direccion" type="text" name="direccion" class="form-control" placeholder="Dirección" value="<?= set_value('direccion', $data['direccion']) ?>">
                        <?php if (isset($validation)) { ?>
                            <span class="text-danger font-weight-bold"><?php echo $validation->getError('direccion'); ?></span>
                        <?php } ?>
                        <label for="direccion">Dirección <b class="text-danger">*</b></label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input id="tasa_interes" type="number" name="tasa_interes" class="form-control" placeholder="10" value="<?= set_value('tasa_interes', $data['tasa_interes']) ?>">
                        <?php if (isset($validation)) { ?>
                            <span class="text-danger font-weight-bold"><?php echo $validation->getError('tasa_interes'); ?></span>
                        <?php } ?>
                        <label for="tasa_interes">Tasa Interes (Porcentaje)<b class="text-danger">*</b></label>
                    </div>
                </div>
                <div class="col-md-6">
    <div class="form-floating mb-3">
        <input id="cuotas" type="text" name="cuotas" class="form-control" placeholder="10" value="<?= set_value('cuotas', $data['cuotas']) ?>">
        <?php if (isset($validation)) { ?>
            <span class="text-danger font-weight-bold"><?php echo $validation->getError('cuotas'); ?></span>
        <?php } ?>
        <label for="cuotas">Cuotas<b class="text-danger">*</b></label>
    </div>
</div>

                <div class="col-md-6">
                    <div class="form-group mb-3">
                    <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" class="form-control" class="form-control" placeholder="Agradecimiento" name="mensaje" rows="3"><?= set_value('mensaje', $data['mensaje']) ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="subdominio" class="form-label">Subdominio</label>
                        <input type="text" class="form-control" id="subdominio" name="subdominio" value="<?= $empresa['subdominio'] ?? '' ?>" readonly>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer text-end">
            <button class="btn btn-primary" type="submit">Modificar</button>
        </div>
    </form>
</div>