@extends('layouts.app')
@section('title', 'Follow-ups')
@section('breadcrumb', 'Follow-ups')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet">
<style>
.fc { --fc-border-color: var(--crm-border); --fc-page-bg-color: var(--crm-card); --fc-neutral-bg-color: var(--crm-bg); --fc-list-event-hover-bg-color: var(--crm-nav-hover); }
.fc .fc-toolbar-title { font-family:'Syne',sans-serif; font-size:18px; font-weight:700; color:var(--crm-text); }
.fc .fc-button { background:var(--crm-card)!important; border:1px solid var(--crm-border)!important; color:var(--crm-text)!important; font-size:12px!important; font-weight:600!important; }
.fc .fc-button:hover { background:var(--crm-nav-hover)!important; color:var(--crm-primary)!important; }
.fc .fc-button-active { background:var(--crm-primary)!important; border-color:var(--crm-primary)!important; color:#fff!important; }
.fc-theme-standard td, .fc-theme-standard th { border-color:var(--crm-border); }
.fc .fc-col-header-cell-cushion { color:var(--crm-muted); font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:.6px; text-decoration:none; }
.fc .fc-daygrid-day-number { color:var(--crm-muted); font-size:13px; text-decoration:none; }
.fc .fc-daygrid-day.fc-day-today { background:rgba(59,130,246,.06); }
.fc .fc-event { border-radius:5px; font-size:12px; font-weight:600; border:none!important; padding:2px 6px; cursor:pointer; }
.fc .fc-daygrid-day:hover { background:var(--crm-nav-hover); cursor:pointer; }

.event-type-dot { width:10px;height:10px;border-radius:50%;flex-shrink:0; }
.color-option { width:22px;height:22px;border-radius:50%;cursor:pointer;border:2px solid transparent;transition:border .15s; }
.color-option.selected { border-color:#fff; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title"><i class="fas fa-calendar-alt me-2" style="color:var(--crm-purple)"></i>Follow-ups</h1>
        <p class="page-subtitle">Schedule meetings, calls, and follow-ups in one place</p>
    </div>
    <button class="btn-crm-primary" onclick="openAddModal()">
        <i class="fas fa-plus"></i> New Follow-up
    </button>
</div>

<div class="row g-3">
    <!-- Sidebar legend -->
    <div class="col-md-3">
        <div class="crm-card mb-3">
            <div class="crm-card-header">
                <i class="fas fa-tag" style="color:var(--crm-purple)"></i>
                <h5 class="card-title">Event Types</h5>
            </div>
            <div class="crm-card-body" style="padding:16px">
                @foreach([
                    ['type'=>'meeting',  'color'=>'#3B82F6','label'=>'Meeting'],
                    ['type'=>'call',     'color'=>'#10B981','label'=>'Call'],
                    ['type'=>'follow_up','color'=>'#EF4444','label'=>'Follow Up'],
                    ['type'=>'reminder', 'color'=>'#EC4899','label'=>'Reminder'],
                    ['type'=>'task',     'color'=>'#F59E0B','label'=>'Task'],
                    ['type'=>'other',    'color'=>'#8B5CF6','label'=>'Other'],
                ] as $t)
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="event-type-dot" style="background:{{ $t['color'] }}"></div>
                    <span style="font-size:13px">{{ $t['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Upcoming events -->
        <div class="crm-card">
            <div class="crm-card-header">
                <i class="fas fa-clock" style="color:var(--crm-warning)"></i>
                <h5 class="card-title">Upcoming</h5>
            </div>
            <div id="upcomingList" style="padding:12px">
                <div style="color:var(--crm-muted);font-size:12px;text-align:center;padding:20px">Loading…</div>
            </div>
        </div>
    </div>

    <!-- Calendar -->
    <div class="col-md-9">
        <div class="crm-card">
            <div class="crm-card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Event Modal -->
<div class="modal fade crm-modal" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalTitle"><i class="fas fa-calendar-plus me-2" style="color:var(--crm-purple)"></i>New Follow-up</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <input type="hidden" id="event_id">
                    <div class="row g-3">
                        <div class="col-12"><label class="crm-label">Title *</label><input type="text" name="title" id="ev_title" class="crm-input" placeholder="Event title…"></div>
                        <div class="col-md-6">
                            <label class="crm-label">Type *</label>
                            <select name="type" id="ev_type" class="crm-input">
                                <option value="meeting">Meeting</option>
                                <option value="call">Call</option>
                                <option value="follow_up">Follow Up</option>
                                <option value="reminder">Reminder</option>
                                <option value="task">Task</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Color</label>
                            <div class="d-flex gap-2 mt-1" id="colorPicker">
                                @foreach(['#3B82F6','#10B981','#EF4444','#F59E0B','#8B5CF6','#06B6D4','#EC4899','#F97316'] as $col)
                                    <div class="color-option {{ $col === '#3B82F6' ? 'selected' : '' }}"
                                        style="background:{{ $col }}" data-color="{{ $col }}"
                                        onclick="selectColor('{{ $col }}')"></div>
                                @endforeach
                            </div>
                            <input type="hidden" name="color" id="ev_color" value="#3B82F6">
                        </div>
                        <div class="col-md-6"><label class="crm-label">Start Date & Time *</label><input type="datetime-local" name="start_datetime" id="ev_start" class="crm-input"></div>
                        <div class="col-md-6"><label class="crm-label">End Date & Time *</label><input type="datetime-local" name="end_datetime" id="ev_end" class="crm-input"></div>
                        <div class="col-md-6"><label class="crm-label">Location</label><input type="text" name="location" id="ev_location" class="crm-input" placeholder="Zoom, Office, etc."></div>
                        <div class="col-md-6">
                            <label class="crm-label">Contact</label>
                            <select name="contact_id" id="ev_contact" class="crm-input">
                                <option value="">— None —</option>
                                @foreach($contacts as $c)<option value="{{ $c->id }}">{{ $c->full_name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Lead</label>
                            <select name="lead_id" id="ev_lead" class="crm-input">
                                <option value="">— None —</option>
                                @foreach($leads as $l)<option value="{{ $l->id }}">{{ Str::limit($l->title,40) }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="crm-label">Deal</label>
                            <select name="deal_id" id="ev_deal" class="crm-input">
                                <option value="">— None —</option>
                                @foreach($deals as $d)<option value="{{ $d->id }}">{{ Str::limit($d->title,40) }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-12"><label class="crm-label">Description</label><textarea name="description" id="ev_desc" class="crm-input" rows="3" placeholder="Details…"></textarea></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="justify-content:space-between">
                <div>
                    <button id="deleteEventBtn" class="btn btn-danger" style="display:none" onclick="deleteCurrentEvent()">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn-crm-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn-crm-primary" onclick="submitEvent()"><i class="fas fa-save"></i> <span id="saveEventLabel">Save Event</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.js"></script>
<script>
const EVENTS_URL = '{{ route("admin.calendar.events") }}';
const CALENDAR_URL = '{{ route("admin.calendar.index") }}';
let currentEventId = null;

document.addEventListener('DOMContentLoaded', function () {
    const cal = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek' },
        events: EVENTS_URL,
        editable: false,
        selectable: true,
        eventClick: function(info) {
            openEditModal(info.event);
        },
        dateClick: function(info) {
            openAddModal(info.dateStr);
        },
        height: 'auto',
        eventDisplay: 'block',
    });
    cal.render();
    loadUpcoming();
});

async function loadUpcoming() {
    const res = await fetch(`${EVENTS_URL}?start=${new Date().toISOString()}&end=${new Date(Date.now()+7*86400000).toISOString()}`);
    const events = await res.json();
    const container = document.getElementById('upcomingList');
    if (!events.length) {
        container.innerHTML = '<div style="color:var(--crm-muted);font-size:12px;text-align:center;padding:20px">No upcoming events</div>';
        return;
    }
    container.innerHTML = events.slice(0,5).map(e => `
        <div style="padding:8px;border-radius:8px;border-left:3px solid ${e.color};background:rgba(255,255,255,.03);margin-bottom:6px;cursor:pointer"
             onclick="openEditModalById(${e.id})">
            <div style="font-size:13px;font-weight:600">${e.title}</div>
            <div style="font-size:11px;color:var(--crm-muted)">${new Date(e.start).toLocaleString('en-US',{month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'})}</div>
            ${e.extendedProps.location ? `<div style="font-size:11px;color:var(--crm-muted)"><i class="fas fa-map-marker-alt"></i> ${e.extendedProps.location}</div>` : ''}
        </div>
    `).join('');
}

function selectColor(color) {
    document.getElementById('ev_color').value = color;
    document.querySelectorAll('.color-option').forEach(el => el.classList.toggle('selected', el.dataset.color === color));
}

function openAddModal(dateStr) {
    currentEventId = null;
    document.getElementById('eventModalTitle').innerHTML = '<i class="fas fa-calendar-plus me-2" style="color:var(--crm-purple)"></i>New Event';
    document.getElementById('saveEventLabel').textContent = 'Save Event';
    document.getElementById('deleteEventBtn').style.display = 'none';
    document.getElementById('event_id').value = '';
    document.getElementById('eventForm').reset();
    selectColor('#3B82F6');
    if (dateStr) {
        document.getElementById('ev_start').value = dateStr + 'T09:00';
        document.getElementById('ev_end').value = dateStr + 'T10:00';
    }
    new bootstrap.Modal(document.getElementById('eventModal')).show();
}

async function openEditModalById(id) {
    const res = await fetch(`${CALENDAR_URL}/${id}/edit`);
    const e = await res.json();
    openEditFromData(e);
}

function openEditModal(event) {
    openEditFromData({
        id: event.id,
        title: event.title,
        type: event.extendedProps.type,
        color: event.backgroundColor,
        start_datetime: event.startStr,
        end_datetime: event.endStr,
        location: event.extendedProps.location,
        description: event.extendedProps.description,
    });
}

function openEditFromData(e) {
    currentEventId = e.id;
    document.getElementById('eventModalTitle').innerHTML = '<i class="fas fa-pen me-2" style="color:var(--crm-warning)"></i>Edit Event';
    document.getElementById('saveEventLabel').textContent = 'Update Event';
    document.getElementById('deleteEventBtn').style.display = 'inline-flex';
    document.getElementById('event_id').value = e.id;
    document.getElementById('ev_title').value = e.title ?? '';
    document.getElementById('ev_type').value = e.type ?? 'meeting';
    document.getElementById('ev_location').value = e.location ?? '';
    document.getElementById('ev_desc').value = e.description ?? '';
    document.getElementById('ev_contact').value = e.contact_id ?? '';
    document.getElementById('ev_lead').value = e.lead_id ?? '';
    document.getElementById('ev_deal').value = e.deal_id ?? '';
    const startVal = e.start_datetime ? e.start_datetime.substring(0,16) : '';
    const endVal   = e.end_datetime   ? e.end_datetime.substring(0,16) : '';
    document.getElementById('ev_start').value = startVal;
    document.getElementById('ev_end').value   = endVal;
    selectColor(e.color ?? '#3B82F6');
    new bootstrap.Modal(document.getElementById('eventModal')).show();
}

async function submitEvent() {
    const id = document.getElementById('event_id').value;
    const isEdit = !!id;
    const data = {
        title:          document.getElementById('ev_title').value,
        type:           document.getElementById('ev_type').value,
        color:          document.getElementById('ev_color').value,
        start_datetime: document.getElementById('ev_start').value,
        end_datetime:   document.getElementById('ev_end').value,
        location:       document.getElementById('ev_location').value,
        description:    document.getElementById('ev_desc').value,
        contact_id:     document.getElementById('ev_contact').value || null,
        lead_id:        document.getElementById('ev_lead').value || null,
        deal_id:        document.getElementById('ev_deal').value || null,
        all_day:        false,
    };
    if (isEdit) data._method = 'PUT';

    const url = isEdit ? `${CALENDAR_URL}/${id}` : CALENDAR_URL;
    const res = await fetch(url, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}, body:JSON.stringify(data) });
    const json = await res.json();
    if (!res.ok) { showToast(Object.values(json.errors||{}).flat()[0] || 'Error', 'error'); return; }
    bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
    showToast(json.message, 'success');
    setTimeout(() => location.reload(), 800);
}

async function deleteCurrentEvent() {
    if (!currentEventId) return;
    const res = await fetch(`${CALENDAR_URL}/${currentEventId}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF} });
    const json = await res.json();
    bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
    showToast(json.message, 'success');
    setTimeout(() => location.reload(), 800);
}
</script>
@endpush
